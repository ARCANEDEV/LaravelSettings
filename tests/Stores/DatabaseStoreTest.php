<?php namespace Arcanedev\LaravelSettings\Tests\Stores;

/**
 * Class     DatabaseStoreTest
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DatabaseStoreTest extends AbstractStoreTest
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
        return $this->getStore('database');
    }

    /** @test */
    public function it_can_set_extra_columns()
    {
        /** @var  \Arcanedev\LaravelSettings\Stores\DatabaseStore  $store */
        $store = $this->createStore();

        $this->assertSame([], $store->all());

        $store->setExtraColumns(['user_id' => 1])->set(['foo' => 'bar'])->save();
        $store->setExtraColumns(['user_id' => 2])->set(['foo' => 'baz'])->save();

        $store = $this->createStore();

        $this->assertSame('baz', $store->get('foo'));
        $this->assertSame('bar', $store->setExtraColumns(['user_id' => 1])->get('foo'));
        $this->assertSame('baz', $store->setExtraColumns(['user_id' => 2])->get('foo'));
    }

    /** @test */
    public function it_can_constraint_query()
    {
        /** @var  \Arcanedev\LaravelSettings\Stores\DatabaseStore  $store */
        $store = $this->createStore();

        $store->set('foo', 'bar');
        $store->set('bar', 'baz');

        $this->assertStoreHasData($store, ['foo' => 'bar', 'bar' => 'baz']);

        $store = $this->createStore();

        $store->setConstraint(function (\Illuminate\Database\Eloquent\Builder $query, $insert) {
            if ( ! $insert) {
                $query->where('key', 'foo');
            }
        });

        $this->assertSame(['foo' => 'bar'], $store->all());
    }
}
