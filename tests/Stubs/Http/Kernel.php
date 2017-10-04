<?php namespace Arcanedev\LaravelSettings\Tests\Stubs\Http;

/**
 * Class     Kernel
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stubs\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    /**
     * The application's middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Orchestra\Testbench\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // Middleware\TrustProxies::class,
        \Arcanedev\LaravelSettings\Middleware\SaveSettings::class,
    ];
}
