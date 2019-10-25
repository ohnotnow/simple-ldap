<?php

namespace Ohffs\Ldap;

use Ohffs\Ldap\LdapConnectionInterface;
use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapException;
use Ohffs\Ldap\LdapLogic;

class FakeLdapConnection implements LdapConnectionInterface
{
    use LdapLogic;

    protected function unbind()
    {
        return;
    }

    protected function connect($server)
    {
        return $this->ldap = $server;
    }

    protected function startTls()
    {
        if ($this->ldap == 'down') {
            return false;
        }
        return true;
    }

    protected function anonymousBind()
    {
        if ($this->ldap == 'down') {
            return false;
        }
        return true;
    }

    protected function authenticatedBind($username, $password)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to LDAP');
        }
        $info = $this->searchForUser($username);
        if (!$info) {
            return false;
        }

        if ($password == 'validpassword') {
            return true;
        }

        return false;
    }

    protected function searchForUser($username)
    {
        if ($username == 'validuser') {
            return [
                0 => [
                    'uid' => [0 => 'validuser'],
                    'mail' => [0 => 'validuser@example.com'],
                    'sn' => [0 => 'surname'],
                    'givenname' => [0 => 'forenames'],
                    'telephonenumber' => [0 => 'phone'],
                    'dn' => [0 => 'validuser'],
                ],
            ];
        }

        return false;
    }
}
