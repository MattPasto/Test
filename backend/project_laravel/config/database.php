<?php

$connections = [
    'default' => env('APP_ENV') === 'testa' ? 'test_db' : 'dev_db',
    'connections' => [
        'test_db' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST_TEST', '127.0.0.1'),
            'port' => env('DB_PORT_TEST', '3306'),
            'database' => env('DB_DATABASE_TEST', 'laravel'),
            'username' => env('DB_USERNAME_TEST', 'root'),
            'password' => env('DB_PASSWORD_TEST', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
    'migrations' => 'migrations',
];

// Verifica l'ambiente di APP_ENV
if (env('APP_ENV') === 'testa') {
    $connections['connections']['dev_db'] = $connections['connections']['test_db'];
} else {
    // Configurazione per l'ambiente non 'test'
    $connections['connections']['dev_db'] = [
        'driver' => 'mysql',
        'url' => env('DB_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ];
}

return $connections;
