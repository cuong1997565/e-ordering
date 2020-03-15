<?php

return [

    'default' => 'mysql',

    'migrations' => 'migrations',

    'connections' => [
        'mysql' => [
            'driver'    => env('DB_CONNECTION'),
            'host'      => env('DB_HOST'),
            'port'      => env('DB_PORT'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'mysql_server_translations' => [
            'driver'    => env('DB_TRANS_CONNECTION'),
            'host'      => env('DB_TRANS_HOST'),
            'port'      => env('DB_TRANS_PORT'),
            'database'  => env('DB_TRANS_DATABASE'),
            'username'  => env('DB_TRANS_USERNAME'),
            'password'  => env('DB_TRANS_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
    ],
];