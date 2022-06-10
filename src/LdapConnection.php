<?php

namespace Ohffs\Ldap;

use Illuminate\Support\Facades\Log;
use Ohffs\Ldap\LdapConnectionInterface;
use Ohffs\Ldap\LdapUser;
use Ohffs\Ldap\LdapException;
use Ohffs\Ldap\LdapLogic;

class LdapConnection implements LdapConnectionInterface
{
    use LdapLogic;

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
        if (!$this->ldap) {
            throw new LdapException('Not connected to LDAP');
        }

        return @ldap_bind($this->ldap, $username, $password);
    }

    protected function searchForUser($username)
    {
        $search = ldap_search($this->ldap, $this->ou, "uid={$username}");
        if (ldap_count_entries($this->ldap, $search) != 1) {
            Log::error("Could not find {$username} in LDAP");
            return false;
        }

        return ldap_get_entries($this->ldap, $search);
    }

    protected function searchForUserByEmail($emailAddress)
    {
        $search = ldap_search($this->ldap, $this->ou, "mail={$emailAddress}");
        if (ldap_count_entries($this->ldap, $search) != 1) {
            Log::error("Could not find {$emailAddress} in LDAP");
            return false;
        }

        return ldap_get_entries($this->ldap, $search);
    }

    protected function search($term)
    {
        $query = "(sn=*$term*)";
        if (preg_match('/[0-9]/', $term)) {
            $query = "(cn=*$term*)";
        }

        $ldapResults = ldap_search($this->ldap, $this->ou, $query);

        if (! $ldapResults) {
            return false;
        }

        if (ldap_count_entries($this->ldap, $ldapResults) == 0) {
            Log::error("Could not find {$term} in LDAP");
            return false;
        }

        return ldap_get_entries($this->ldap, $ldapResults);
    }
}
