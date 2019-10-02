# 3. Usage

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

### Basic methods

You can access the setting instance (Store instance) by injecting the contract `Arcanedev\LaravelSettings\Contracts\Store` to your `__construct()` method or with the `app()` helper.

Something like:

```php
<?php

use Arcanedev\LaravelSettings\Contracts\Store;

class RandomNameClass
{
    /** @var  \Arcanedev\LaravelSettings\Contracts\Store */
    protected $settings;

    public function __construct(Store $settings) 
    {
        $this->settings = $settings;
    }

    public function main()
    {
        $fooValue = $this->settings->get('foo', 'default value');
        // ...
    }
}
```

Oh wait, there is a helper function:

```php
<?php

class RandomNameClass 
{
    public function main()
    {
        $fooValue = settings()->get('foo', 'Default value');
        // ...
    }
}
```

```php
<?php

// To set a value
settings()->set('foo', 'bar'); // OR settings()->set(['foo' => 'bar']);

// To get a value
$fooValue = settings()->get('foo');

// To get a value or the default if not found
$fooValueOrDefault = settings()->get('foo', 'Default value');

// To get all stored values
$allSettings = settings()->all();

// Check if exists
settings()->has('foo');

// To forget a stored value
settings()->forget('foo');

// Delete all
settings()->flush();

// Save all the changes
settings()->save();
```

**IMPORTANT:** You need to call `settings()->save()` explicitly to save all the changes.

#### Auto-saving

If you're using Laravel `>= 5.5`, you can add the `Arcanedev\LaravelSettings\Middleware\SaveSettings` middleware to your middleware list in `app\Http\Kernel.php` to save automatically at the end of all HTTP requests/responses stuff.

Don't forget that outside of the HTTP layer, the `settings` middleware it'll not be **TRIGGERED**.

If you made changes in console commands, queues ... you need to call the `settings()->save()` to persist the data.

#### Bonus

You can check if the settings has unsaved data by calling the `settings()->isSaved()`.

### About the storage

As you may know, this package has multiple storage support: `json`, `database`, `redis`, `array`.

And you can set one of these options as a *default* storage.

#### JSON storage

This storage has a special method which is `settings()->setPath($pathToStoreTheJsonFile)`.

So you can change the path on the fly and handle multiple storage files.

```php
$fooValue = settings()->setPath($pathOne)->get('foo');

settings()->setPath($pathTwo)->set('bar', 'Hello there');

// Didn't i mention the method chaining ? Read the source code you lazy foo'!
``` 

#### Database storage

* Step 1: run the package's migration.
* Step 2: ???
* Step 3: PROFIT !!!

##### User's Settings

If you want to store `settings` for multiple users in the same database you can do so by:

```php
settings()->setExtraColumns(['user_id' => 1]);
settings()->set(['foo' => 'bar']);
settings()->save();

settings()->setExtraColumns(['user_id' => 2]);
settings()->set(['foo' => 'baz']);
settings()->save();

settings()->setExtraColumns(['user_id' => 1]);
settings()->get('foo'); // returns 'bar'

settings()->setExtraColumns(['user_id' => 2]);
settings()->get('foo'); // returns 'baz'
```

The `user_id` is a column found in the migration and has `0` as a default value.

##### More database stuff

If you need to do more crazy queries with the database storage, you can use the `settings()->setConstraint()`:

```php
settings()->set(['foo' => 'bar', 'baz' => 'qux'])->save();

settings()->setConstraint(function (\Illuminate\Database\Eloquent\Builder $query, $insert) {
    if ( ! $insert) {
        $query->where('key', 'foo');
    }
});

$values = settings()->all(); // returns `['foo' => 'bar']`
```

The `$insert` is a `boolean` telling you whether if the `query` is an insert or not. If it is, you usually don't need to do any constraints to the `$query` builder.

#### Custom storage

This package also allows you to implement your own custom storage.

##### 1. Create the Custom Store class

```php
<?php namespace App\Settings;

use Arcanedev\LaravelSettings\Stores\AbstractStore;

class CustomStore extends AbstractStore
{
    /**
     * Fire the post options to customize the store.
     *
     * @param  array  $options
     */
    protected function postOptions(array $options)
    {
        //
    }
    
    /**
     * Read the data from the store.
     *
     * @return array
     */
    protected function read()
    {
        //
    }
    
    /**
     * Write the data into the store.
     *
     * @param  array  $data
     *
     * @return void
     */
    protected function write(array $data)
    {
        //
    }
} 
```

If you don't want to use the abstract `Arcanedev\LaravelSettings\Stores\AbstractStore` class and create a class/methods from scratch, use the `Arcanedev\LaravelSettings\Contracts\Store` contract instead.

```php
<?php namespace App\Settings;

use Arcanedev\LaravelSettings\Contracts\Store;

class CustomStore implements Store
{
    // Implement the contract's methods here
} 
```

##### 2. Register the Custom Store

Go to the `config/settings.php` config file and edit the `drivers` list:

```php
return [
    'drivers' => [
        'custom' => [
            'driver'  => App\Settings\CustomStore::class,
        ],
    ],
];
```

If you used the the abstract `Arcanedev\LaravelSettings\Stores\AbstractStore` class, you can pass a `options` array like credential keys, path ...

```php
return [
    'drivers' => [
        'custom' => [
            'driver'  => App\Settings\CustomStore::class,
            'options' => [
                // more customi[s|z]ation
            ],
        ],
    ],
];
``` 

Last and not least, you can set it as the `default` store.

### Is there any stores manager ?

OH BOI!

You remember about the custom store stuff, you can register it on the fly (the service provider is the best place):

```php
settings()->extend('custom', function () {
    return new App\Settings\CustomStore();
});
```

**`PHP7` style you said ?**

```php
settings()->extend('custom-store-with-abstract-class', function () {
    return new class extends Arcanedev\LaravelSettings\Stores\AbstractStore {
        // implement the methods here
    };
});

settings()->extend('custom-store-with-contract', function () {
    return new class implements Arcanedev\LaravelSettings\Contracts\Store {
        // same here
    };
});
```

**WAIT!! How i'm gonna use the custom store ?**

Easy, you pass the **key** of the registered custom class:

```php
$fooValue = settings('custom')->get('foo');

//...
```

**BUT i'm injecting the store contract in the (Controller, Service ...) constructor, so i got the default store instead**

Instead of the `Arcanedev\LaravelSettings\Contracts\Store`, use `Arcanedev\LaravelSettings\Contracts\Manager` contract:

```php
<?php

use Arcanedev\LaravelSettings\Contracts\Manager;

class RandomNameClass
{
    /** @var  \Arcanedev\LaravelSettings\Contracts\Store */
    protected $settings;

    public function __construct(Manager $manager) 
    {
        $this->settings = $manager->driver('custom');
    }

    public function main()
    {
        $fooValue = $this->settings->get('foo', 'default value');
        // ...
    }
}
```

### The end ?
