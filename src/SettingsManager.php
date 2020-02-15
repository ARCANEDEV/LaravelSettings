<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings;

use Arcanedev\LaravelSettings\Contracts\Store as StoreContract;
use Arcanedev\LaravelSettings\Contracts\Manager as SettingsManagerContract;
use Illuminate\Support\{Arr, Manager};

/**
 * Class     SettingsManager
 *
 * @package  Arcanedev\LaravelSettings
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsManager extends Manager implements SettingsManagerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Indicates if migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('settings.default', 'json');
    }

    /**
     * Register a new store.
     *
     * @param  string  $driver
     * @param  array   $params
     *
     * @return $this
     */
    public function registerStore(string $driver, array $params)
    {
        return $this->extend($driver, function () use ($params) : StoreContract {
            return $this->container->make($params['driver'], [
                'options' => Arr::get($params, 'options', []),
            ]);
        });
    }
}
