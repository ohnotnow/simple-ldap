<?php

namespace Ohffs\Ldap;

class LdapUser
{
    protected $username;

    protected $email;

    protected $surname;

    protected $forenames;

    public function __construct(array $ldapAttribs)
    {
        foreach (['dn', 'mail', 'sn', 'givenname'] as $key) {
            if (array_key_exists($key, $ldapAttribs[0])) {
                $this->$key = $ldapAttribs[0][$key][0];
            }
        }
    }

    public function __get($attribute)
    {
        return $this->$attribute;
    }
}