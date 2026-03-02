---
name: simple-laravel-ldap
description: Use this skill when asked to work with LDAP.
---
 
# Simple LDAP

The simple-laravel-ldap PHP package is a convenience wrapper around the base PHP LDAP functions.  It provides helpers for the common LDAP use cases.

## Examples

The package provides an \Ldap:: laravel facade:

```php

if (\Ldap::authenticate('username', 'password')) {
  // whatever
}

$user = \Ldap::findUser('jenny123');
dump($user);
/*
Ohffs\Ldap\LdapUser {#739
  #username: "jenny123"
  #email: "Jenny@example.com"
  #surname: "Smith"
  #forenames: "Jenny"
  #phone: "012345678"
}
*/
print $user['email'];
// 'Jenny@example.com'
print $user->email;
// 'Jenny@example.com'
print $user->toArray();
/*
[
     "username" => "jenny123",
     "email" => "Jenny@example.com",
     "surname" => "Smith",
     "forenames" => "Jenny",
     "phone" => "012345678",
]
*/
```

Or you can use the service via DI:

```php
use Ohffs\Ldap\LdapService;

public function __construct(LdapService $ldap)
{
    $this->ldap = $ldap;
}

public function something()
{
   if ($this->ldap->authenticate('username', 'password')) {
       // ...
   }
}
```

Some other methods are available:

```php
$user = \Ldap::findUserByEmail('jenny@example.com');
$users = \Ldap::searchForUsers('jenny');
```

