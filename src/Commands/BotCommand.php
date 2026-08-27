<?php

namespace Mk4U\LaraBot\Commands;

use Mk4U\TGram\Bot;
use Mk4U\TGram\Commands\InstallCommand;
use Illuminate\Console\Command;

/**
 * Proxy de comandos 
 */
class BotCommand extends Command
{
    // DEFINICIÓN DEL COMANDO ARTISAN
    protected $signature = 'tgram {args?*}';
    protected $description = 'Run TGram commands through Laravel';

    // Comandos que deben ejecutarse localmente en Laravel
    private const LOCAL_COMMANDS = [
        'hook:info',
        'hook:set',
        'hook:delete',
        'hook:about',
        'register',
        'poll',
    ];

    public function handle(): int
    {
        $args = $this->argument('args');

        
        // Lógica especial para instalación
        if (empty($args)) {
            if (!file_exists(config_path('bot.php'))) {
                return $this->runInstallation();
            }

            $app = Bot::NAME.' v'.Bot::VERSION;

            $this->info("$app is installed. Available commands");
            $this->line('  php artisan tgram:register');
            $this->line('  php artisan tgram:hook:set <url>');
            $this->line('  php artisan tgram:hook:delete');
            $this->line('  php artisan tgram:hook:info');
            $this->line('  php artisan tgram:hook:about');
            $this->line('  php artisan tgram:poll <interval>');
            $this->line('  php artisan tgram:command <name>');
            $this->line('  php artisan tgram:callback <name> <action>');
            $this->line('  php artisan tgram:conversation <name>');
            $this->line('  php artisan tgram:handler <name>');
            $this->line('  php artisan tgram:middleware <name>');

            return 0;
        }

        // Obtener el primer argumento (el comando)
        $command = $args[0];

        // Verificar si es un comando local
        if (in_array($command, self::LOCAL_COMMANDS)) {
            return $this->runLocalCommand($command, array_slice($args, 1));
        }

        $this->error("Unknown command: $command");
        $this->line('Run php artisan tgram to see available commands.');
        return 1;
    }

    protected function runInstallation(): int
    {
        $this->info('Installing TGram for Laravel...');

        // Configurar Laravel API (Sanctum)
        $this->call('install:api');

        // Publicar configuración de TGram
        $this->call('vendor:publish', [
            '--provider' => 'Mk4U\LaraBot\BotServiceProvider',
            '--tag' => 'bot-config'
        ]);

        $tgramInstall = new InstallCommand();
        $tgramInstall->createDirectories();
        $tgramInstall->mwConfig();
        $tgramInstall->loggerMiddleware();
        $tgramInstall->makeCommandClasses();
        $tgramInstall->updateComposerAutoload();

        $this->info('TGram Laravel dependencies installed!');
        $this->line('');
        $this->line('Next steps:');
            $this->line('1. Configure your BOT_TOKEN in .env file');
            $this->line('2. Run: php artisan tgram:hook:set <your-webhook-url>');
            $this->line('3. Create your first command: php artisan tgram:command');
            $this->line('4. Run: php artisan tgram:register');

        return 0;
    }

    private function runLocalCommand(string $command, array $args = []): int
    {
        // Convertir hook:info -> tgram:hook:info
        $artisanCommand = 'tgram:' . $command;

        $mapped = [];
        foreach ($args as $i => $arg) {
            $mapped[$i === 0 ? 'url' : 'arg' . $i] = $arg;
        }

        // Ejecutar el comando Artisan directamente
        // Laravel se encargará de pasar los argumentos correctamente
        return $this->call($artisanCommand, $mapped);
    }
}
