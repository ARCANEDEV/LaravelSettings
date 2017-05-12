<?php namespace Arcanedev\LaravelSettings\Stores;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;

/**
 * Class     RedisStore
 *
 * @package  Arcanedev\LaravelSettings\Stores
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
        $this->manager = new RedisManager(Arr::pull($options, 'client', 'predis'), $options);
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
        $data = $this->manager->connection()
                              ->command('get', ['settings']);

        return is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    protected function write(array $data)
    {
        $this->manager->connection()
                      ->command('set', ['settings', json_encode($data)]);
    }
}
