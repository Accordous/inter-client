<?php

namespace Accordous\InterClient\Tests;

use Accordous\InterClient\Providers\InterClientServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * add the package provider
     *
     * @param  Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            InterClientServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('inter.host', 'https://cdpj.partners.bancointer.com.br');
        $app['config']->set('inter.api', '/v2');
        $app['config']->set('inter.token', '!@@@#$%*@#$@$');
    }
}
