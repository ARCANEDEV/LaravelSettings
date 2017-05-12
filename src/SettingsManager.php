<?php namespace Arcanedev\LaravelSettings;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Arcanedev\LaravelSettings\Contracts\Manager as SettingsManagerContract;

/**
 * Class     SettingsManager
 *
 * @package  Arcanedev\LaravelSettings
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsManager extends Manager implements SettingsManagerContract
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
    public function getDefaultDriver()
    {
        return $this->config()->get('settings.default', 'json');
    }

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * SettingsManager constructor.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        foreach ($this->getConfig('drivers') as $driver => $configs) {
            $this->registerDriver($driver, $configs);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }

    /**
     * Get the package configs.
     *
     * @param  string      $key
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    private function getConfig($key, $default = null)
    {
        return $this->config()->get("settings.{$key}", $default);
    }

    /**
     * Register the driver.
     *
     * @param  string  $driver
     * @param  array   $configs
     */
    private function registerDriver($driver, array $configs)
    {
        $this->extend($driver, function () use ($configs) {
            return new $configs['driver'](
                $this->app, Arr::get($configs, 'options', [])
            );
        });
    }
}
