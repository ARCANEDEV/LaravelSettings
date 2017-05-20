# 2. Configuration

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  
## Settings

### Default:

```php
<?php

// config/settings.php

return [
    /* -----------------------------------------------------------------
     |  Default drivers
     | -----------------------------------------------------------------
     | Supported: 'array', 'json', 'database', 'redis'
     */

    'default' => 'json',

    // ...
];
```

You can specify here your default store driver that you would to use.

### Drivers:

```php
<?php

// config/settings.php

return [
    //...
    
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
                'path'   => storage_path('app/settings.json'),
            ],
        ],

        'database' => [
            'driver'  => Arcanedev\LaravelSettings\Stores\DatabaseStore::class,

            'options' => [
                'connection' => null,
                'table'      => 'settings',
                'model'      => \Arcanedev\LaravelSettings\Models\Setting::class,
            ],
        ],

        'redis' => [
            'driver'  => Arcanedev\LaravelSettings\Stores\RedisStore::class,

            'options' => [
                'client' => 'predis',

                'default' => [
                    'host'     => env('REDIS_HOST', '127.0.0.1'),
                    'port'     => env('REDIS_PORT', 6379),
                    'database' => env('REDIS_DB', 0),
                ],
            ],
        ],
    ],
];
```

This is the list of the supported store drivers. You can expand this list by adding a custom store driver.

The store config is structured like this:

```php
<?php 

// ...
'custom' => [
    'driver'  => App\Stores\CustomStore::class,
    
    'options' => [
        // ...
    ],
],
```
