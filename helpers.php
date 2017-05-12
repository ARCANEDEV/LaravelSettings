<?php

if ( ! function_exists('settings')) {
    /**
     * Get the settings manager instance.
     *
     * @param  string|null  $driver
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Manager|\Arcanedev\LaravelSettings\Contracts\Store
     */
    function settings($driver = null) {
        /** @var  \Arcanedev\LaravelSettings\Contracts\Manager  $manager */
        $manager = app(Arcanedev\LaravelSettings\Contracts\Manager::class);

        return $driver ? $manager->driver($driver) : $manager;
    }
}
