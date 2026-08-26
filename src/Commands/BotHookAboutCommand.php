<?php

namespace Mk4U\LaraBot\Commands;

use Illuminate\Console\Command;

class BotHookAboutCommand extends Command
{
    protected $signature = 'tgram:hook:about';
    protected $description = 'Gets information about the Telegram bot';

    use ValidatesBotToken;

    public function handle()
    {
        $this->ensureBotToken();

        try {
            $bot = app('xbot');
            $data = $bot->getMe();

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
