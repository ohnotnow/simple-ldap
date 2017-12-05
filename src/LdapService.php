<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapConnectionInterface;
use Ohffs\Ldap\LdapException;

class LdapService
{
    protected $connection;

    public function __construct(LdapConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function authenticate(string $username, string $password)
    {
        return $this->connection->authenticate($username, $password);
    }

    public function findUser(string $username)
    {
        return $this->connection->findUser($username);
    }
}