<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests;

use Arcanedev\LaravelSettings\Contracts\Manager;
use Arcanedev\LaravelSettings\Middleware\SaveSettings;
use Arcanedev\LaravelSettings\SettingsServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../database/migrations');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make(Kernel::class)->pushMiddleware(SaveSettings::class);
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | -----------------------------------------------------------------
     */

    /**
     * Get the settings manager instance.
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Manager
     */
    protected function getSettingsManager(): Manager
    {
        return $this->app->make(Manager::class);
    }
}
