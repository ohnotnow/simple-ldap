<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapConnectionInterface;
use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapException;

class FakeLdapConnection implements LdapConnectionInterface
{
    public function authenticate(string $username, string $password)
    {
        if ($username == 'validuser') {
            if ($password == 'invalidpassword') {
                return false;
            }
            return true;
        }

        if ($username == 'serverdown') {
            throw new LdapException('Could not connect to server');
        }

        return false;
    }

    public function findUser(string $username)
    {
        if ($username == 'validuser') {
            return new LdapUser([
                0 => [
                    'dn' => [0 => 'validuser'],
                    'mail' => [0 => 'validuser@example.com'],
                    'sn' => [0 => 'surname'],
                    'givenname' => [0 => 'forenames'],
                ],
            ]);
        }

        return false;
    }
}
