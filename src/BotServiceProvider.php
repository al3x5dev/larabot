<?php

namespace Mk4U\LaraBot;

use Mk4U\LaraBot\Commands\BotCommand;
use Mk4U\LaraBot\Commands\BotHookAboutCommand;
use Mk4U\LaraBot\Commands\BotHookDeleteCommand;
use Mk4U\LaraBot\Commands\BotHookInfoCommand;
use Mk4U\LaraBot\Commands\BotHookSetCommand;
use Mk4U\LaraBot\Commands\BotRegisterCommand;
use Mk4U\LaraBot\Commands\BotTelegramCallbacksCommand;
use Mk4U\LaraBot\Commands\BotTelegramCommandsCommand;
use Mk4U\LaraBot\Commands\BotTelegramConversationsCommand;
use Mk4U\LaraBot\Commands\BotTelegramHandlerCommand;
use Mk4U\LaraBot\Commands\BotTelegramMiddlewareCommand;
use Mk4U\LaraBot\Commands\BotPollCommand;
use Al3x5\LaravelPsr16Cache;
use Mk4U\TGram\Bot;
use Illuminate\Support\ServiceProvider;

class BotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publicar configuración
        $this->publishes([
            __DIR__ . '/../config/bot.php' => config_path('bot.php'),
        ], 'bot-config');

        // Registrar comandos de Artisan
        $this->commands([
            BotCommand::class,
            BotHookAboutCommand::class,
            BotHookDeleteCommand::class,
            BotHookInfoCommand::class,
            BotHookSetCommand::class,
            BotRegisterCommand::class,
            BotTelegramCallbacksCommand::class,
            BotTelegramConversationsCommand::class,
            BotTelegramHandlerCommand::class,
            BotTelegramMiddlewareCommand::class,
            BotTelegramCommandsCommand::class,
            BotPollCommand::class
        ]);
    }

    public function register()
    {
        // Fusionar configuración
        $this->mergeConfigFrom(__DIR__ . '/../config/bot.php', 'bot');

        $this->app->singleton(Bot::class, function ($app) {
            $config = config('bot');
            $config['cache'] = new LaravelPsr16Cache($app['cache']->store());
            return new Bot($config);
        });

        $this->app->alias(Bot::class, 'tgram');
    }
}
