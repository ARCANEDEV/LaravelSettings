<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Middleware;

use Arcanedev\LaravelSettings\Contracts\Store;
use Closure;

/**
 * Class     SaveSettings
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SaveSettings
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelSettings\Contracts\Store */
    protected $settings;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * SaveSettings constructor.
     *
     * @param  \Arcanedev\LaravelSettings\Contracts\Store  $settings
     */
    public function __construct(Store $settings)
    {
        $this->settings = $settings;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure                   $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Hasta la vista, baby.
     *
     * @param  \Illuminate\Http\Request                    $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     */
    public function terminate($request, $response)
    {
        $this->settings->save();
    }
}
