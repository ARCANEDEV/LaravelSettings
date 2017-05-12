<?php namespace Arcanedev\LaravelSettings\Stores;

use Arcanedev\LaravelSettings\Contracts\Store;
use Arcanedev\LaravelSettings\Utilities\Arr;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     AbstractStore
 *
 * @package  Arcanedev\LaravelSettings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractStore implements Store
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Whether the store has changed since it was last loaded.
     *
     * @var bool
     */
    protected $unsaved = false;

    /**
     * Whether the settings data are loaded.
     *
     * @var bool
     */
    protected $loaded = false;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * AbstractStore constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  array                                         $options
     */
    public function __construct(Application $app, array $options = [])
    {
        $this->app = $app;
        $this->postOptions($options);
    }

    /**
     * Fire the post options to customize the store.
     *
     * @param  array  $options
     */
    abstract protected function postOptions(array $options);

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
    public function get($key, $default = null)
    {
        $this->checkLoaded();

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has($key)
    {
        $this->checkLoaded();

        return Arr::has($this->data, $key);
    }

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param  string|array  $key
     * @param  mixed         $value
     *
     * @return self
     */
    public function set($key, $value = null)
    {
        $this->checkLoaded();
        $this->unsaved = true;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($this->data, $k, $v);
            }
        }
        else {
            Arr::set($this->data, $key, $value);
        }

        return $this;
    }

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     *
     * @return self
     */
    public function forget($key)
    {
        $this->unsaved = true;

        Arr::forget($this->data, $key);

        return $this;
    }

    /**
     * Flushing all data.
     *
     * @return self
     */
    public function flush()
    {
        $this->unsaved = true;
        $this->data    = [];

        return $this;
    }

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all()
    {
        $this->checkLoaded();

        return $this->data;
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return self
     */
    public function save()
    {
        if ( ! $this->isSaved()) {
            $this->write($this->data);
            $this->unsaved = false;
        }

        return $this;
    }

    /**
     * Read the data from the store.
     *
     * @return array
     */
    abstract protected function read();

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     *
     * @return void
     */
    abstract protected function write(array $data);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the data is saved.
     *
     * @return bool
     */
    public function isSaved()
    {
        return ! $this->unsaved;
    }

    /**
     * Check if the settings data has been loaded.
     */
    protected function checkLoaded()
    {
        if ($this->isLoaded()) return;

        $this->data   = $this->read();
        $this->loaded = true;
    }

    /**
     * Reset the loaded status.
     */
    protected function resetLoaded()
    {
        $this->loaded = false;
    }

    /**
     * Check if the data is loaded.
     *
     * @return bool
     */
    protected function isLoaded()
    {
        return (bool) $this->loaded;
    }
}
