<?php namespace Arcanedev\LaravelSettings\Tests\Stores;

/**
 * Class     RedisStoreTest
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedisStoreTest extends AbstractStoreTest
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a store instance.
     *
     * @param  array $data
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Store
     */
    protected function createStore(array $data = [])
    {
        return $this->getStore('redis');
    }

    public function tearDown()
    {
        $this->createStore()->flush()->save();

        parent::tearDown();
    }
}
