<?php

return [

    /* -----------------------------------------------------------------
     |  Default drivers
     | -----------------------------------------------------------------
     | Supported: 'array', 'json', 'database'
     */

    'default' => 'json',

    /* -----------------------------------------------------------------
     |  Drivers
     | -----------------------------------------------------------------
     */

    'drivers' => [

        'array' => [
            'driver'  => Arcanedev\LaravelSettings\Stores\ArrayStore::class,
        ],

        'json' => [
            'driver'  => Arcanedev\LaravelSettings\Stores\JsonStore::class,

            'options' => [
                'path'   => storage_path('settings.json'),
            ],
        ],

        'database' => [
            'driver'  => Arcanedev\LaravelSettings\Stores\DatabaseStore::class,

            'options' => [
                'connection' => null,
                'table'      => 'settings',
                'model'      => \Arcanedev\LaravelSettings\Models\Setting::class,
            ]
        ],

    ],
];
