<?php

return [
    'settings' => [
        'displayErrorDetails' => $_ENV['APP_DEBUG'] === 'true',
        'logErrors' => true,
        'logErrorDetails' => true,
        'logger' => [
            'name' => 'shopflow',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'jwt' => [
            'secret' => $_ENV['JWT_SECRET'],
            'expiration' => (int)$_ENV['JWT_EXPIRATION'],
            'refresh_expiration' => (int)$_ENV['JWT_REFRESH_EXPIRATION'],
        ],
        'redis' => [
            'host' => $_ENV['REDIS_HOST'],
            'port' => (int)$_ENV['REDIS_PORT'],
            'password' => $_ENV['REDIS_PASSWORD'] ?? null,
        ],
    ],
];
