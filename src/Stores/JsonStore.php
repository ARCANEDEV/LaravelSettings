<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Stores;

use Arcanedev\LaravelSettings\Utilities\Arr;
use RuntimeException;

/**
 * Class     JsonStore
 *
 * @package  Arcanedev\LaravelSettings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonStore extends AbstractStore
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  string */
    protected $path;

    /* -----------------------------------------------------------------
     |  Post-Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Fire the post options to customize the store.
     *
     * @param  array $options
     */
    protected function postOptions(array $options)
    {
        $this->setPath(Arr::get($options, 'path'));
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the storage path for the json file.
     *
     * @param  string  $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Read the data from the store.
     *
     * @return array
     */
    protected function read()
    {
        $contents = $this->filesystem()->get($this->path);
        $data     = json_decode($contents, true);

        if (is_null($data)) {
            throw new RuntimeException("Invalid JSON file in [{$this->path}]");
        }

        return (array) $data;
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    protected function write(array $data)
    {
        $contents = $data ? json_encode($data) : '{}';

        $this->filesystem()->put($this->path, $contents);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    private function filesystem()
    {
        return $this->app['files'];
    }
}
