<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapService;
use Ohffs\Ldap\LdapConnectionInterface;
use Illuminate\Support\ServiceProvider;

class LdapServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ldap.php' => config_path('ldap.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind(LdapService::class, function ($app) {
            $connection = $app->make(LdapConnectionInterface::class);
            return new LdapService($connection);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/config/ldap.php',
            'ldap'
        );
    }

    public function provides()
    {
        return [LdapService::class];
    }
}