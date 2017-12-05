<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapService;
use Ohffs\Ldap\LdapConnectionInterface;
use Illuminate\Support\ServiceProvider;

class LdapServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(LdapService::class, function ($app) {
            $connection = $app->make(LdapConnectionInterface::class);
            return new LdapService($connection);
        });
    }

    public function provides()
    {
        return [LdapService::class];
    }
}