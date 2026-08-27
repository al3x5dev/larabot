<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Commands\Traits\MakeClass;
use Illuminate\Console\Command;

class BotTelegramMiddlewareCommand extends Command
{
    use MakeClass;
    use ValidatesName;

    protected $signature = 'tgram:middleware {name?}';
    protected $description = 'Create a new middleware';

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $name = $this->ask('Middleware name (e.g. auth or auth/user)');
            if (empty($name)) {
                $this->error('Name cannot be empty.');
                return 1;
            }
        }

        if (!$this->validateName($name)) {
            $this->error('Invalid name. Only letters, numbers, hyphens, underscores and forward slashes are allowed.');
            return 1;
        }

        if (!str_ends_with($name, 'Middleware')) {
            $name .= '-middleware';
        }

        $data = $this->makeDir($name, 'bot/Middlewares', $this->output);
        if (empty($data)) {
            return 1;
        }

        $this->makeTelegramMiddleware($data);
        $this->info("Middleware [{$data['filename']}] created successfully.");

        return 0;
    }
}
