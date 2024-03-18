<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests\Stores;

use Arcanedev\LaravelSettings\Contracts\Store;
use Arcanedev\LaravelSettings\Stores\DatabaseStore;
use Arcanedev\LaravelSettings\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use UnexpectedValueException;

/**
 * Class     AbstractStoreTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractStoreTestCase extends TestCase
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
     * @return \Arcanedev\LaravelSettings\Contracts\Store|mixed
     */
    protected abstract function createStore(array $data = []);

    /**
     * Get the store instance.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\LaravelSettings\Contracts\Store
     */
    protected function getStore(string $driver)
    {
        return $this->getSettingsManager()->driver($driver);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_init_with_empty_data(): void
    {
        $store = $this->createStore();

        static::assertEquals([], $store->all());
    }

    /** @test */
    public function it_can_save_the_changes(): void
    {
        $store = $this->createStore();

        $store->set('foo', 'bar');

        static::assertStoreHasDataWithKey($store, 'foo', 'bar');
    }

    /** @test */
    public function it_can_check_data_exists(): void
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');

        static::assertStoreHasData($store, ['foo' => ['bar' => 'baz']]);

        static::assertTrue($store->has('foo'));
        static::assertTrue($store->has('foo.bar'));
        static::assertFalse($store->has('foo.baz'));
    }

    /** @test */
    public function it_can_set_with_nested_keys(): void
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');

        static::assertStoreHasData($store, ['foo' => ['bar' => 'baz']]);
    }

    /** @test */
    public function it_must_throw_an_exception_when_setting_nested_key_on_non_array_member(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Non-array segment encountered');

        $store = $this->createStore();

        $store->set('foo', 'bar');
        $store->set('foo.bar', 'baz');
    }

    /** @test */
    public function it_can_forget(): void
    {
        $store = $this->createStore();

        $store->set('foo', 'bar');
        $store->set('bar', 'baz');

        static::assertStoreHasData($store, ['foo' => 'bar', 'bar' => 'baz']);

        $store->forget('foo');

        static::assertStoreHasData($store, ['bar' => 'baz']);
    }

    /** @test */
    public function it_can_forget_nested_key(): void
    {
        $store = $this->createStore();

        $store->set('foo.bar', 'baz');
        $store->set('foo.baz', 'bar');
        $store->set('bar.foo', 'baz');

        static::assertStoreHasData($store, [
            'foo' => [
                'bar' => 'baz',
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('foo.bar');

        static::assertStoreHasData($store, [
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

        static::assertStoreHasData($store, $expected);
    }

    /** @test */
    public function it_can_flush_all_data(): void
    {
        $store = $this->createStore();

        $store->set(['foo' => 'bar']);

        static::assertStoreHasData($store, ['foo' => 'bar']);

        $store->flush();

        static::assertStoreHasData($store, []);
    }

    /** @test */
    public function it_can_save_automatically_with_middleware(): void
    {
        Route::middleware('web')->any('/testing-route-with-save-settings-middleware', function () {
            return 'I know the route uri is long. http://pa1.narvii.com/6522/44d52ea2f090856abea164e7660233c85bbdd9d5_00.gif';
        });

        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $store = $this->createStore();

            $store->set(['foo' => 'bar']);

            $response = $this->call($method, '/testing-route-with-save-settings-middleware');
            $response->assertSuccessful();

            $store = $this->createStore();

            static::assertEquals(['foo' => 'bar'], $store->all());

            // Make sure to flush data
            $store->flush()->save();
            static::assertEquals([], $store->all());
        }
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
    protected function assertStoreHasData(Store $store, $expected, $message = '')
    {
        static::assertEquals($expected, $store->all(), $message);
        static::assertFalse($store->isSaved());

        $store->save();

        static::assertEquals($expected, $this->createStore()->all(), $message);
        static::assertTrue($store->isSaved());
    }

    /**
     * @param  \Arcanedev\LaravelSettings\Contracts\Store  $store
     * @param  string                                      $key
     * @param  mixed                                       $expected
     * @param  string|null                                 $message
     */
    protected function assertStoreHasDataWithKey(Store $store, string $key, $expected, $message = ''): void
    {
        static::assertSame($expected, $store->get($key), $message);
        static::assertFalse($store->isSaved());

        $store->save();

        static::assertSame($expected, $this->createStore()->get($key), $message);
        static::assertTrue($store->isSaved());
    }
}
