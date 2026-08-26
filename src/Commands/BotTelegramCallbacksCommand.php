<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Commands\Traits\MakeClass;
use Illuminate\Console\Command;

class BotTelegramCallbacksCommand extends Command
{
    use MakeClass;

    protected $signature = 'tgram:callback {name?} {action?}';
    protected $description = 'Create a new Telegram callback';

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $name = $this->ask('Callback class name (supports subdirs: Games/Dice)');
            if (empty($name)) {
                $this->error('Name cannot be empty.');
                return 1;
            }
        }

        $action = $this->argument('action');
        if (empty($action)) {
            $action = $this->ask('Callback action name (e.g. play, join, confirm)');
            if (empty($action)) {
                $this->error('Action cannot be empty.');
                return 1;
            }
        }

        $data = $this->makeDir($name, 'bot/Callbacks', $this->output);
        if (empty($data)) {
            return 1;
        }

        $this->makeCallback($data, $action);
        $this->info("Telegram callback [{$data['filename']}] created successfully.");

        return 0;
    }
}
