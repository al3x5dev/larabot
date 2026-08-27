# TGram Laravel

Seamless integration of [TGram](https://github.com/al3x5dev/tgram) the powerful PHP library for creating Telegram bots with the [Laravel framework](https://github.com/laravel/laravel).


## 🚀 Features

- **Artisan Commands**: Use TGram through familiar Laravel commands
- **Laravel Cache Integration**: Automatic PSR-16 adapter
- **Laravel Configuration**: Native Laravel configuration system
- **Dependency Injection**: Type-hint Bot in controllers for automatic injection
- **Service Container**: Bot registered as singleton in Laravel container
- **Webhook Management**: Easy webhook configuration for Telegram bots


## 📦 Installation

```bash
composer require mk4u/larabot
```


## ⚡ Quick Start

### 1. Install and Configure

```bash
php artisan tgram
```

This command will:
- Configure the Laravel API (Sanctum)
- Publish the TGram configuration
- Create bot directory structure (commands, callbacks, middlewares)
- Create default command classes (Start, Help)
- Generate middleware configuration

### 2. Configure your Bot

Add to your `.env` file:
```env
BOT_TOKEN=1234567890:ABCDEFGHIJKLMNOQRSTZ
```

### 3. Register default commands and callback functions

```bash
php artisan tgram:register
```

### 4. Configure Webhook

```bash
php artisan tgram:hook:set https://yourdomain.com/bot/api/webhook
```

### 5. Create Your Commands

```bash
php artisan tgram:command HelloWorld
```


## 🛠 Available Commands

| Command | Description |
|---------|-------------|
| `php artisan tgram` | Install and configure TGram |
| `php artisan tgram:register` | Register all commands and callbacks |
| `php artisan tgram:hook:set <url>` | Set webhook URL |
| `php artisan tgram:hook:delete` | Delete webhook |
| `php artisan tgram:hook:info` | Get webhook info |
| `php artisan tgram:hook:about` | Get bot info |
| `php artisan tgram:poll <interval>` | Start long polling |
| `php artisan tgram:command <name>` | Create new command |
| `php artisan tgram:callback <name> <action>` | Create new callback handler |
| `php artisan tgram:conversation <name>` | Create new conversation |
| `php artisan tgram:handler <name>` | Create new handler |
| `php artisan tgram:middleware <name>` | Create new middleware |


## ⚙️ Settings

After installation, add to your `.env`:
```env
BOT_TOKEN=1234567890:ABCDEFGHIJKLMNOQRSTZ
BOT_SECRET=your_secret_key (optional)
BOT_MANAGERS=123456789,985632147 (optional)
BOT_WEBHOOK_URL=https://yourdomain.com/bot (optional)
```

**Parameter descriptions:**
- **BOT_TOKEN** — Your Telegram bot token (issued by @BotFather). **Required.**
- **BOT_SECRET** — Secret token to verify webhook authenticity (`X-Telegram-Bot-Api-Secret-Token` header). Optional but recommended.
- **BOT_MANAGERS** — Telegram user IDs of bot administrators, comma-separated. Enables the `isAdmin()` method to restrict commands. Optional.
- **BOT_WEBHOOK_URL** — Public URL where Telegram will send updates. Only needed if using webhook instead of polling. Optional.


## 🌐 Webhook Route

Add to your `routes/api.php`:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/bot', function (Request $request) {
    app('tgram')->run();
});
```


## 🔧 Dependency Injection

### Type-hinting in Controllers

The Bot is registered as a singleton in Laravel's service container. You can inject it directly:

```php
<?php

namespace App\Http\Controllers;

use Mk4U\TGram\Bot;

class TelegramController extends Controller
{
    public function sendMessage(Bot $bot)
    {
        $bot->sendMessage(
            chat_id: '123456789',
            text: 'Hello from Laravel!'
        );

        return response()->json(['status' => 'sent']);
    }
}
```


### Using the Service Container

```php
use Mk4U\TGram\Bot;

// Method 1: via type-hinting (recommended)
public function myMethod(Bot $bot) { ... }

// Method 2: via alias 'tgram' (recommended)
$bot = app('tgram');

// Method 3: via app() helper
$bot = app(Bot::class);
```

> **Note:** Always use `app('tgram')` or `app(Bot::class)` instead of `new Bot(...)`. The singleton handles Laravel cache integration automatically.


### Using Facade (if needed)

Create a Facade in your Laravel app `app/Facades/Bot.php`:

```php
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Bot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mk4U\TGram\Bot::class;
    }
}
```

Then use: `Bot::sendMessage([...])`


## 🎯 Usage Examples

### Send a Message

```php
public function example(Bot $bot)
{
    $bot->sendMessage(
        chat_id:'123456789',
        text:'Hello World!'
    );
}
```


### Send with Keyboard

```php
public function withKeyboard(Bot $bot)
{
    use Mk4U\TGram\Core\Factories\InlineButton;

    $keyboard = Mk4U\TGram\Core\Factories\Keyboard::inline()
    ->row([
        Mk4U\TGram\Core\Factories\InlineButton::make('👥 Community')
        ->url('https://t.me/myGroup')
    ])->build();

    $bot->sendMessage(
        chat_id:'123456789',
        text:'Join my group!',
        reply_markup:$keyboard
    );
}
```


### Answer Callback Query

```php
public function handleCallback(Bot $bot)
{
    $bot->answerCallbackQuery(
        callback_query_id:$callback_query_id,
        text:'Action completed!'
    );
}
```


## 📚 Requirements

- PHP 8.5+
- Laravel 13.x+
- TGram Library (automatically installed)


## 🆘 Support

- [Documentation](https://github.com/al3x5dev/tgram/tree/main/docs)
- [Issues](https://github.com/al3x5dev/tgram/issues)


## 📄 License

MIT License - see the [LICENSE](LICENSE) file for details.

---

**Need help?** Open an issue on GitHub or consult the TGram documentation.