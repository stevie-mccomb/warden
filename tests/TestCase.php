<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Stevie\Warden\Providers\WardenServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Load the package's service provider.
     */
    protected function getPackageProviders($app)
    {
        return [
            WardenServiceProvider::class,
        ];
    }
}
