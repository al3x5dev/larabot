<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Commands\Traits\MakeClass;
use Illuminate\Console\Command;

class BotTelegramCommandsCommand extends Command
{
    use MakeClass;
    use ValidatesName;

    protected $signature = 'tgram:command {name?}';
    protected $description = 'Create a new Telegram command';

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $name = $this->ask('What should the Telegram command be named? [Eg. Start] (supports subdirs: Admin/User/Ban)');
            if (empty($name)) {
                $this->error('Name cannot be empty.');
                return 1;
            }
        }

        if (!$this->validateName($name)) {
            $this->error('Invalid name. Only letters, numbers, hyphens, underscores and forward slashes are allowed.');
            return 1;
        }

        $data = $this->makeDir($name, 'bot/Commands', $this->output);
        if (empty($data)) {
            return 1;
        }

        $this->makeTelegramCommand($data);
        $this->info("Telegram command [{$data['filename']}] created successfully.");

        return 0;
    }
}
