<?php

namespace Ohffs\Ldap;

use Illuminate\Support\Facades\Facade;
use Ohffs\Ldap\LdapService;

class LdapFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LdapService::class;
    }
}