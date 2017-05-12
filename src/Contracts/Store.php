<?php namespace Arcanedev\LaravelSettings\Contracts;

/**
 * Interface     Store
 *
 * @package  Arcanedev\LaravelSettings\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Store
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get a specific key from the settings data.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param  string|array  $key
     * @param  mixed         $value
     *
     * @return self
     */
    public function set($key, $value = null);

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     *
     * @return self
     */
    public function forget($key);

    /**
     * Flushing all data.
     *
     * @return self
     */
    public function flush();

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all();

    /**
     * Save any changes done to the settings data.
     *
     * @return self
     */
    public function save();

    /* -----------------------------------------------------------------
     |  Check Functions
     | -----------------------------------------------------------------
     */

    /**
     * Check if the data is saved.
     *
     * @return bool
     */
    public function isSaved();
}
