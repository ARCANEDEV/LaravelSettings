<?php namespace Arcanedev\LaravelSettings\Tests\Stores;

use Arcanedev\LaravelSettings\Contracts\Store;
use Arcanedev\LaravelSettings\Stores\DatabaseStore;
use Arcanedev\LaravelSettings\Tests\TestCase;

/**
 * Class     AbstractStoreTest
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractStoreTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a store instance.
     *
     * @param  array  $data
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Store
     */
    protected abstract function createStore(array $data = []);

    /**
     * Get the store instance.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Store
     */
    protected function getStore($driver)
    {
        return $this->getSettingsManager()->driver($driver);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_init_with_empty_data()
    {
        $store = $this->createStore();

        $this->assertEquals([], $store->all());
    }

    /** @test */
    public function it_can_save_the_changes()
    {
        $store = $this->createStore();

        $store->set('foo', 'bar');

        $this->assertStoreHasDataWithKey($store, 'foo', 'bar');
    }

    /** @test */
    public function it_can_check_data_exists()
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');

        $this->assertStoreHasData($store, ['foo' => ['bar' => 'baz']]);

        $this->assertTrue($store->has('foo'));
        $this->assertTrue($store->has('foo.bar'));
        $this->assertFalse($store->has('foo.baz'));
    }

    /** @test */
    public function it_can_set_with_nested_keys()
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');

        $this->assertStoreHasData($store, ['foo' => ['bar' => 'baz']]);
    }

    /**
     * @test
     *
     * @expectedException         \UnexpectedValueException
     * @expectedExceptionMessage  Non-array segment encountered
     */
    public function it_must_throw_an_exception_when_setting_nested_key_on_non_array_member()
    {
        $store = $this->createStore();

        $store->set('foo', 'bar');
        $store->set('foo.bar', 'baz');
    }

    /** @test */
    public function it_can_forget()
    {
        $store = $this->createStore();

        $store->set('foo', 'bar');
        $store->set('bar', 'baz');

        $this->assertStoreHasData($store, ['foo' => 'bar', 'bar' => 'baz']);

        $store->forget('foo');

        $this->assertStoreHasData($store, ['bar' => 'baz']);
    }

    /** @test */
    public function it_can_forget_nested_key()
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');
        $store->set('foo.baz', 'bar');
        $store->set('bar.foo', 'baz');

        $this->assertStoreHasData($store, [
            'foo' => [
                'bar' => 'baz',
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('foo.bar');

        $this->assertStoreHasData($store, [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('bar.foo');

        $expected = [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [],
        ];

        if ($store instanceof DatabaseStore) unset($expected['bar']);

        $this->assertStoreHasData($store, $expected);
    }

    /** @test */
    public function it_can_flush_all_data()
    {
        $store = $this->createStore();

        $store->set(['foo' => 'bar']);

        $this->assertStoreHasData($store, ['foo' => 'bar']);

        $store->flush();

        $this->assertStoreHasData($store, []);
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */
    /**
     * @param  \Arcanedev\LaravelSettings\Contracts\Store  $store
     * @param  mixed                                       $expected
     * @param  string|null                                 $message
     */
    protected function assertStoreHasData(Store $store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);
        $this->assertFalse($store->isSaved());

        $store->save();

        $this->assertEquals($expected, $this->createStore()->all(), $message);
        $this->assertTrue($store->isSaved());
    }

    /**
     * @param  \Arcanedev\LaravelSettings\Contracts\Store  $store
     * @param  string                                      $key
     * @param  mixed                                       $expected
     * @param  string|null                                 $message
     */
    protected function assertStoreHasDataWithKey(Store $store, $key, $expected, $message = null)
    {
        $this->assertSame($expected, $store->get($key), $message);
        $this->assertFalse($store->isSaved());

        $store->save();

        $this->assertSame($expected, $this->createStore()->get($key), $message);
        $this->assertTrue($store->isSaved());
    }
}
