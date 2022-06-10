<?php

namespace Ohffs\Ldap;

trait LdapLogic
{
    protected $ldap;

    protected $ou;

    public function __construct(string $server, string $ou, string $username = null, string $password = null)
    {
        $this->ldap = $this->connect($server);
        if (!$this->ldap) {
            throw new LdapException('Could not connect to server');
        }

        if (!$this->startTls()) {
            throw new LdapException("Could not start TLS on ldap binding");
        }

        if ($username) {
            $result = $this->authenticatedBind($username, $password);
        } else {
            $result = $this->anonymousBind();
        }

        if (!$result) {
            throw new LdapException('Could not bind to server');
        }

        $this->ou = "O={$ou}";
    }

    public function authenticate(string $username, string $password)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $username = trim(strtolower($username));
        if (!$username) {
            return false;
        }
        if (!$password) {
            return false;
        }

        $user = $this->searchForUser($username);
        if (!$user) {
            return false;
        }

        if (!$this->authenticatedBind($user[0]['dn'], $password)) {
            return false;
        }

        return true;
    }

    public function findUser(string $username)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $username = trim(strtolower($username));
        if (!$username) {
            return false;
        }

        $info = $this->searchForUser($username);

        if (!$info) {
            return false;
        }

        return new LdapUser($info);
    }

    public function findUserByEmail(string $email)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $email = trim(strtolower($email));
        if (!$email) {
            return false;
        }

        $info = $this->searchForUserByEmail($email);

        if (!$info) {
            return false;
        }

        return new LdapUser($info);
    }

    public function searchForUsers(string $term)
    {
        if (!$this->ldap) {
            throw new LdapException('Not connected to ldap server');
        }

        $term = trim(strtolower($term));
        if (!$term) {
            return false;
        }

        $info = $this->search($term);

        if (!$info) {
            return false;
        }

        $ldapUsers = [];
        foreach ($info as $ldapEntry) {
            if (!is_array($ldapEntry)) {
                continue;
            }
            $ldapUsers[] = new LdapUser([0 => $ldapEntry]);
        }

        return $ldapUsers;
    }
}
