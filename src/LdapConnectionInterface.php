<?php

namespace Ohffs\Ldap;

interface LdapConnectionInterface
{
    public function authenticate(string $username, string $password);

    public function findUser(string $username);
}