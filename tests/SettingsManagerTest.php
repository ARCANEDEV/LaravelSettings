<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSettings\Tests;

/**
 * Class     SettingsManagerTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SettingsManagerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelSettings\Contracts\Manager */
    protected $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->getSettingsManager();
    }

    protected function tearDown(): void
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            \Arcanedev\LaravelSettings\Contracts\Manager::class,
            \Arcanedev\LaravelSettings\SettingsManager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_can_be_instantiated_with_helper(): void
    {
        $expectations = [
            \Arcanedev\LaravelSettings\Contracts\Manager::class,
            \Arcanedev\LaravelSettings\SettingsManager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, settings());
        }
    }

    /** @test */
    public function it_can_get_default_store_name(): void
    {
        static::assertSame('json', $this->manager->getDefaultDriver());
    }

    /** @test */
    public function it_can_get_default_store_by_contract(): void
    {
        $store = $this->app->make(\Arcanedev\LaravelSettings\Contracts\Store::class);

        $expectations = [
            \Arcanedev\LaravelSettings\Contracts\Store::class,
            \Arcanedev\LaravelSettings\Stores\JsonStore::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $store);
        }
    }

    /** @test */
    public function it_can_get_store_by_name(): void
    {
        $expectations = [
            'array'    => \Arcanedev\LaravelSettings\Stores\ArrayStore::class,
            'database' => \Arcanedev\LaravelSettings\Stores\DatabaseStore::class,
            'json'     => \Arcanedev\LaravelSettings\Stores\JsonStore::class,
        ];

        foreach ($expectations as $name => $expected) {
            $store = $this->manager->driver($name);

            static::assertInstanceOf(\Arcanedev\LaravelSettings\Contracts\Store::class, $store);
            static::assertInstanceOf($expected, $store);
        }
    }

    /** @test */
    public function it_can_get_store_by_name_via_helper(): void
    {
        $expectations = [
            'array'    => \Arcanedev\LaravelSettings\Stores\ArrayStore::class,
            'database' => \Arcanedev\LaravelSettings\Stores\DatabaseStore::class,
            'json'     => \Arcanedev\LaravelSettings\Stores\JsonStore::class,
        ];

        foreach ($expectations as $name => $expected) {
            $store = settings($name);

            static::assertInstanceOf(\Arcanedev\LaravelSettings\Contracts\Store::class, $store);
            static::assertInstanceOf($expected, $store);
        }
    }
}
