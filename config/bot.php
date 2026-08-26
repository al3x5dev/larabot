<?php

return [
    'token' => env('BOT_TOKEN', ''),
    'secret' => env('BOT_SECRET', null),
    'admins' => array_map('intval', array_filter(array_map('trim', explode(',', env('BOT_MANAGERS', ''))))),
    'debug' => env('APP_DEBUG', true),
    'abs_path' => base_path(),
];
