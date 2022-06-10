<?php

namespace Ohffs\Ldap;

class LdapUser implements \ArrayAccess
{
    protected $username;

    protected $email;

    protected $surname;

    protected $forenames;

    protected $phone;

    protected $validKeys = [
        'uid' => 'username',
        'mail' => 'email',
        'sn' => 'surname',
        'givenname' => 'forenames',
        'telephonenumber' => 'phone',
    ];

    public function __construct(array $ldapAttribs)
    {
        foreach ($this->validKeys as $key => $property) {
            if (array_key_exists($key, $ldapAttribs[0])) {
                $this->$property = $ldapAttribs[0][$key][0];
            }
        }
    }

    public function __get($attribute)
    {
        return $this->$attribute;
    }

    public function toArray()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'surname' => $this->surname,
            'forenames' => $this->forenames,
            'phone' => $this->phone,
        ];
    }

    public function offsetSet($offset, $value): void
    {
        if (in_array($offset, array_values($this->validKeys))) {
            $this->$offset = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return in_array($offset, array_values($this->validKeys));
    }

    public function offsetUnset($offset): void
    {
        $this->$offset = null;
    }

    public function offsetGet($offset): mixed
    {
        return $this->$offset;
    }
}
