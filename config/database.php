<?php

date_default_timezone_set('Asia/Jakarta');

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false
        ],
        'postgres' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_PG_HOST'),
            'database' => env('DB_PG_DATABASE'), // This seems to be ignored
            'port'     => env('DB_PG_PGSQL_PORT', 5432),
            'username' => env('DB_PG_USERNAME'),
            'password' => env('DB_PG_PASSWORD'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public'
        ]
    ]
];
