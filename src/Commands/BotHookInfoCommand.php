<?php

namespace Mk4U\LaraBot\Commands;

use Illuminate\Console\Command;

class BotHookInfoCommand extends Command
{
    protected $signature = 'tgram:hook:info';
    protected $description = "Gets information about the Telegram bot's webhook";

    use ValidatesBotToken;

    public function handle(): int
    {
        if ($this->ensureBotToken() !== null) {
            return 1;
        }

        try {
            $bot = app('tgram');
            $data = $bot->getWebhookInfo();

            foreach ($data->getProperties() as $key => $value) {
                $this->line("<fg=green>$key:</> <fg=white>$value</>");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
