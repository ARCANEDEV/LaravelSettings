<?php namespace Arcanedev\LaravelSettings\Contracts;

use Closure;

/**
 * Interface     SettingsManager
 *
 * @package  Arcanedev\LaravelSettings\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Manager
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver();

    /**
     * Get all of the created "drivers".
     *
     * @return array
     */
    public function getDrivers();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get a driver instance.
     *
     * @param  string|null  $driver
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Store
     */
    public function driver($driver = null);

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string    $driver
     * @param  \Closure  $callback
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Manager
     */
    public function extend($driver, Closure $callback);

    /**
     * Register a new store.
     *
     * @param  string  $driver
     * @param  array   $params
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Manager
     */
    public function registerStore(string $driver, array $params);
}
