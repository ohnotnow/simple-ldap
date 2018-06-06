<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapConnection;
use Ohffs\Ldap\LdapConnectionInterface;
use Illuminate\Support\ServiceProvider;

class LdapConnectionProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(LdapConnectionInterface::class, function ($app) {
            return new LdapConnection(
                config('ldap.server'),
                config('ldap.ou'),
                config('ldap.username', null),
                config('ldap.password', null)
            );
        });
    }

    public function provides()
    {
        return [LdapConnectionInterface::class];
    }
}