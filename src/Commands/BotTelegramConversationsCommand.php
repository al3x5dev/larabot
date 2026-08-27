<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Commands\Traits\MakeClass;
use Illuminate\Console\Command;

class BotTelegramConversationsCommand extends Command
{
    use MakeClass;
    use ValidatesName;

    protected $signature = 'tgram:conversation {name?}';
    protected $description = 'Create a new conversational flow in your bot';

    public function handle(): int
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $name = $this->ask('What will you call the new conversation? [Eg. Chat] (supports subdirs: Admin/User/Delete)');
            if (empty($name)) {
                $this->error('Name cannot be empty.');
                return 1;
            }
        }

        if (!$this->validateName($name)) {
            $this->error('Invalid name. Only letters, numbers, hyphens, underscores and forward slashes are allowed.');
            return 1;
        }

        $data = $this->makeDir($name, 'bot/Conversations', $this->output);
        if (empty($data)) {
            return 1;
        }

        $this->makeConversation($data);
        $this->info("The new conversational flow has been created successfully.");

        return 0;
    }
}
