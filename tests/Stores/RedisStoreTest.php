<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests\Stores;

/**
 * Class     RedisStoreTest
 *
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

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->createStore()->flush()->save();

        parent::tearDown();
    }
}
