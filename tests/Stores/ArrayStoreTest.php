<?php namespace Arcanedev\LaravelSettings\Tests\Stores;

use Arcanedev\LaravelSettings\Contracts\Store;

/**
 * Class     ArrayStoreTest
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrayStoreTest extends AbstractStoreTest
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
        return $this->getStore('array')->set($data)->save();
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    protected function assertStoreHasData(Store $store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);

        $store->save();
    }

    protected function assertStoreHasDataWithKey(Store $store, $key, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->get($key), $message);

        $store->save();
    }
}
