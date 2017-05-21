<?php namespace Arcanedev\LaravelSettings\Stores;

use Illuminate\Redis\Database;

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
     * @var  \Illuminate\Redis\Database
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
        $this->manager = new Database($options);
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
        $data = $this->manager->command('get', ['settings']);

        return is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    protected function write(array $data)
    {
        $this->manager->command('set', ['settings', json_encode($data)]);
    }
}
