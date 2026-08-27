<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Config;
use Illuminate\Console\Command;

class BotRegisterCommand extends Command
{
    protected $signature = 'tgram:register';
    protected $description = 'Register commands and callbacks for your bot';

    public function handle(): int
    {
        if (Config::get('abs_path') === null) {
            Config::init(array_merge(config('bot'), [
                'abs_path' => base_path(),
            ]));
        }

        if (!file_exists(base_path('bot/Commands')) && !file_exists(base_path('bot/Callbacks'))) {
            $this->error('No bot commands or callbacks found. Run php artisan tgram first to set up your bot.');
            return 1;
        }

        if (file_exists(base_path('bot/Commands'))) {
            register('bot/Commands', 'commands');
            $this->info('Telegram commands successfully registered');
        }

        if (file_exists(base_path('bot/Callbacks'))) {
            register('bot/Callbacks', 'callbacks');
            $this->info('Telegram callbacks successfully registered');
        }

        return 0;
    }
}
