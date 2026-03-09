<?php

$serverName = strtolower((string) ($_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? ''));
$isLocal = PHP_SAPI === 'cli'
    || in_array($serverName, ['', 'localhost', '127.0.0.1'], true)
    || str_ends_with($serverName, '.local');

$defaults = $isLocal
    ? [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'capela_market',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ]
    : [
        'host' => 'sql100.infinityfree.com',
        'port' => 3306,
        'dbname' => 'if0_41346911_capela_market',
        'username' => 'if0_41346911',
        'password' => 'KqOUvDamixveSv',
        'charset' => 'utf8mb4',
    ];

return [
    'host' => getenv('DB_HOST') ?: $defaults['host'],
    'port' => (int) (getenv('DB_PORT') ?: $defaults['port']),
    'dbname' => getenv('DB_NAME') ?: $defaults['dbname'],
    'username' => getenv('DB_USER') ?: $defaults['username'],
    'password' => getenv('DB_PASS') ?: $defaults['password'],
    'charset' => getenv('DB_CHARSET') ?: $defaults['charset'],
];
