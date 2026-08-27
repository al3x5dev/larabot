<?php
namespace Mk4U\LaraBot\Commands;

trait ValidatesBotToken
{
    protected function ensureBotToken(): ?int
    {
        if (empty(config('bot.token'))) {
            $this->error('❌ Bot token is not configured');
            $this->line('Please add BOT_TOKEN=your-token to your .env file');
            return 1;
        }
        return null;
    }
}