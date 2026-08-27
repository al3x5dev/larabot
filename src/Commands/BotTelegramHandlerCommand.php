<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Commands\Traits\MakeClass;
use Illuminate\Console\Command;

class BotTelegramHandlerCommand extends Command
{
    use MakeClass;
    use ValidatesName;

    protected $signature = 'tgram:handler {name?}';
    protected $description = 'Create a new Telegram handler';

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $name = $this->ask("What should Telegram's update handler be called? [e.g., ChannelPost]");
            if (empty($name)) {
                $this->error('Name cannot be empty.');
                return 1;
            }
        }

        if (!$this->validateName($name)) {
            $this->error('Invalid name. Only letters, numbers, hyphens, underscores and forward slashes are allowed.');
            return 1;
        }

        $data = $this->makeDir($name, 'bot/Handlers', $this->output);
        if (empty($data)) {
            return 1;
        }

        $this->makeTelegramHandler($data);
        $this->info("Handler [{$data['filename']}] created successfully.");

        return 0;
    }
}
