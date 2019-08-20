<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapConnectionInterface;

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

    public function findUserByEmail(string $email)
    {
        return $this->connection->searchForUserByEmail($email);
    }
}
