<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Stores;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;

/**
 * Class     RedisStore
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedisStore extends AbstractStore
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The redis manager.
     *
     * @var  \Illuminate\Redis\RedisManager
     */
    protected $manager;

    /* -----------------------------------------------------------------
     |  Post-Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Fire the post options to customize the store.
     *
     * @param  array  $options
     */
    protected function postOptions(array $options)
    {
        $this->manager = new RedisManager(
            $this->app, Arr::pull($options, 'client', 'predis'), $options
        );
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
        $data = $this->command('get', ['settings']);

        return is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    protected function write(array $data)
    {
        $this->command('set', ['settings', json_encode($data)]);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get a Redis connection by name.
     *
     * @param  string|null  $name
     *
     * @return \Illuminate\Redis\Connections\Connection
     */
    protected function connection($name = null)
    {
        return $this->manager->connection($name);
    }

    /**
     * Run a command against the Redis database.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    protected function command(string $method, array $parameters = [])
    {
        return $this->connection()->command($method, $parameters);
    }
}
