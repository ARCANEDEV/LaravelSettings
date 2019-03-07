<?php namespace Arcanedev\LaravelSettings\Tests\Stores;

/**
 * Class     JsonStoreTest
 *
 * @package  Arcanedev\LaravelSettings\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class JsonStoreTest extends AbstractStoreTest
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
    protected function createStore(array $data = [])
    {
        file_put_contents($path = $this->getFixtureFilePath(), $json = $data ? json_encode($data) : '{}');

        return $this->getStore('json')->setPath($path);
    }

    protected function tearDown(): void
    {
        unlink($this->getFixtureFilePath());

        parent::tearDown();
    }

    /**
     * @return string
     */
    private function getFixtureFilePath()
    {
        return __DIR__.'/../fixtures/database/json-settings.json';
    }

    /**
     * @test
     *
     * @expectedException  \RuntimeException
     */
    public function it_must_throw_an_exception_when_file_is_invalid()
    {
        /** @var  \Arcanedev\LaravelSettings\Stores\JsonStore  $store */
        $store = $this->createStore();

        $store->setPath(__DIR__.'/../fixtures/database/invalid-settings.json')->all();
    }
}
