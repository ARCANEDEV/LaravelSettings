<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests\Stores;

use Arcanedev\LaravelSettings\Contracts\Store;

/**
 * Class     ArrayStoreTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrayStoreTest extends AbstractStoreTestCase
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

    protected function assertStoreHasData(Store $store, $expected, $message = ''): void
    {
        static::assertEquals($expected, $store->all(), $message);

        $store->save();
    }

    protected function assertStoreHasDataWithKey(Store $store, $key, $expected, $message = ''): void
    {
        static::assertEquals($expected, $store->get($key), $message);

        $store->save();
    }
}
