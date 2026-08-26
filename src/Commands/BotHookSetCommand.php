<?php

namespace Mk4U\LaraBot\Commands;

use Illuminate\Console\Command;

class BotHookSetCommand extends Command
{
    protected $signature = 'tgram:hook:set {url?}';
    protected $description = 'Set up the webhook for the Telegram bot from Laravel';

    use ValidatesBotToken;

    public function handle()
    {
        $url = $this->argument('url');

        if (empty($url)) {
            $url = $this->ask('What is the URL for sending updates?');
        }

        // Validar URL
        if (!filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https') {
            $this->error('The URL must be a valid HTTPS URL.');
            return 1;
        }

        $this->ensureBotToken();

        try {
            $bot = app('xbot');
            $data = $bot->setWebhook($url, drop_pending_updates: true, secret_token: config('bot.secret'));

            $this->info('✅ Webhook was set');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
