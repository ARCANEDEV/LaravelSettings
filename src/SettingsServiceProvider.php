<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings;

use Arcanedev\LaravelSettings\Contracts\Manager as ManagerContract;
use Arcanedev\LaravelSettings\Contracts\Store as StoreContract;
use Arcanedev\Support\Providers\PackageServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     SettingsServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsServiceProvider extends PackageServiceProvider implements DeferrableProvider
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
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();

        $this->registerSettingsManager();
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        SettingsManager::$runsMigrations ? $this->loadMigrations() : $this->publishMigrations();

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            ManagerContract::class,
            StoreContract::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the Settings Manager & Store drivers.
     */
    private function registerSettingsManager(): void
    {
        $this->singleton(ManagerContract::class, SettingsManager::class);

        $this->app->extend(ManagerContract::class, function (ManagerContract $manager, $app) {
            foreach ($app['config']->get('settings.drivers', []) as $driver => $params) {
                $manager->registerStore($driver, $params);
            }

            return $manager;
        });

        $this->singleton(StoreContract::class, function ($app): StoreContract {
            return $app[ManagerContract::class]->driver();
        });
    }
}
