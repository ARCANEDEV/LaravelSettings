<?php namespace Arcanedev\LaravelSettings;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     SettingsServiceProvider
 *
 * @package  Arcanedev\LaravelSettings
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsServiceProvider extends PackageServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'settings';

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();

        $this->registerSettingsManager();

    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\Manager::class,
            Contracts\Store::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the Settings Manager.
     *
     */
    private function registerSettingsManager()
    {
        $this->singleton(Contracts\Manager::class, function ($app) {
            return new SettingsManager($app);
        });

        $this->singleton(Contracts\Store::class, function ($app) {
            /** @var \Arcanedev\LaravelSettings\Contracts\Manager $manager */
            $manager = $app[Contracts\Manager::class];

            return $manager->driver();
        });
    }
}
