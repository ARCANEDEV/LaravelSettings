<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests\Stores;

/**
 * Class     JsonStoreTest
 *
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
     * @return \Arcanedev\LaravelSettings\Contracts\Store|\Arcanedev\LaravelSettings\Stores\JsonStore
     */
    protected function createStore(array $data = [])
    {
        file_put_contents($path = $this->getFixtureFilePath(), $json = $data ? json_encode($data) : '{}');

        return $this->getStore('json')->setPath($path);
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        unlink($this->getFixtureFilePath());

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_must_throw_an_exception_when_file_is_invalid(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->createStore()
             ->setPath(__DIR__.'/../fixtures/database/invalid-settings.json')
             ->all();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    private function getFixtureFilePath(): string
    {
        return __DIR__.'/../fixtures/database/json-settings.json';
    }
}
