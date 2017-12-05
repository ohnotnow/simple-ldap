<?php

namespace Ohffs\Ldap;

use Illuminate\Support\Facades\Log;
use Ohffs\Ldap\LdapConnectionInterface;
use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapException;

class LdapConnection implements LdapConnectionInterface
{
    protected $ldap;

    protected $ou;

    public function __construct(string $server, string $ou)
    {
        $this->ldap = $this->connect($server);
        if (! $this->ldap) {
            throw new LdapException('Could not connect to server');
        }

        if (! $this->startTls()) {
            throw new LdapException("Could not start TLS on ldap binding");
        }

        if (!$this->anonymousBind()) {
            throw new LdapException('Could not bind to server');
        }

        $this->ou = "O={$ou}";
    }

    public function authenticate(string $username, string $password)
    {
        if (! $this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $username = trim(strtolower($username));
        if (!$username) {
            return false;
        }
        if (!$password) {
            return false;
        }

        if (!$this->authenticatedBind($username, $password)) {
            return false;
        }

        $this->unbind();

        return true;
    }

    public function findUser(string $username)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $username = trim(strtolower($username));
        if (! $username) {
            return false;
        }

        $search = ldap_search($this->ldap, $this->ou, "uid={$username}");
        if (ldap_count_entries($this->ldap, $search) != 1) {
            $this->unbind();
            Log::error("Could not find {$username} in LDAP");
            return false;
        }

        $info = ldap_get_entries($this->ldap, $search);

        $this->unbind();

        return new LdapUser($info);
    }

    protected function unbind()
    {
        ldap_unbind($this->ldap);
    }

    protected function connect($server)
    {
        return ldap_connect($server);
    }

    protected function startTls()
    {
        return ldap_start_tls($this->ldap);
    }

    protected function anonymousBind()
    {
        return ldap_bind($this->ldap);
    }

    protected function authenticatedBind($username, $password)
    {
        return @ldap_bind($this->ldap, "uid={$username}", $password);
    }
}