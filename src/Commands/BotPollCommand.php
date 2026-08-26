<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Bot;
use Mk4U\TGram\Core\Entities\Update;
use Mk4U\TGram\Exceptions\BotException;
use Illuminate\Console\Command;

class BotPollCommand extends Command
{
    protected $signature = 'tgram:poll {interval=3}';
    protected $description = 'Starts the bot using long polling instead of a webhook';

    public function handle(): int
    {
        $interval = $this->argument('interval');

        if (!is_numeric($interval) || (int)$interval < 0 || (int)$interval != (float)$interval) {
            $this->error('The interval must be a positive integer (seconds).');
            return 1;
        }
        $interval = (int)$interval;

        $this->handleSignals();

        $bot = app('xbot');
        $offset = 0;

        $this->info(sprintf(
            'Polling started (interval: %ds). Press Ctrl+C to stop.',
            $interval
        ));

        while (true) {
            try {
                $updates = $bot->getUpdates($offset, timeout: $interval);
            } catch (\Throwable $th) {
                $wait = ($th instanceof BotException && $th->retryAfter > 0)
                    ? $th->retryAfter
                    : max(1, $interval);

                $this->error(sprintf(
                    'WARNING: API error: %s — retrying in %ds...',
                    $th->getMessage(),
                    $wait
                ));
                sleep($wait);
                continue;
            }

            foreach ($updates as $raw) {
                $id = $raw['update_id'] ?? '?';
                $type = null;

                try {
                    $update = new Update($raw);
                    $type = $update->type();
                    $bot->run($update);

                    $this->info(sprintf(
                        'update_id: %s, type: %s',
                        $id,
                        $type ?? 'unknown'
                    ));

                    $offset = (is_int($id) ? $id : (int)$id) + 1;
                } catch (\Throwable $th) {
                    $this->error(sprintf(
                        'ERROR: update_id: %s, type: %s — %s',
                        $id,
                        $type ?? 'unknown',
                        $th->getMessage()
                    ));
                }
            }

            if ($interval === 0 && empty($updates)) {
                sleep(1);
            }
        }
    }

    private function handleSignals(): void
    {
        if (!function_exists('pcntl_async_signals')) {
            return;
        }

        pcntl_async_signals(true);

        $stop = function (): never {
            $this->info('Polling stopped.');
            exit(0);
        };

        pcntl_signal(SIGINT, $stop);
        pcntl_signal(SIGTERM, $stop);
    }
}
