<?php namespace Arcanedev\LaravelSettings;

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
        return $this->container['config']->get('settings.default', 'json');
    }

    /**
     * Register a new store.
     *
     * @param  string  $driver
     * @param  array   $params
     *
     * @return \Arcanedev\LaravelSettings\SettingsManager
     */
    public function registerStore(string $driver, array $params)
    {
        return $this->extend($driver, function () use ($params) {
            return $this->container->make($params['driver'], [
                'options' => Arr::get($params, 'options', []),
            ]);
        });
    }
}
