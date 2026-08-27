<?php

namespace Mk4U\LaraBot\Commands;

use Illuminate\Console\Command;

class BotHookDeleteCommand extends Command
{
    protected $signature = 'tgram:hook:delete';
    protected $description = 'Delete the webhook for the Telegram bot';

    use ValidatesBotToken;

    public function handle(): int
    {
        if (!$this->confirm('Are you sure you want to delete the webhook?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        if ($this->ensureBotToken() !== null) {
            return 1;
        }

        try {
            $bot = app('tgram');
            $result = $bot->deleteWebhook(drop_pending_updates: true);

            if ($result !== true) {
                $this->error('Failed to delete webhook.');
                return 1;
            }

            $this->info('Webhook was deleted');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
