<?php

use Ohffs\Ldap\LdapService;
use Ohffs\Ldap\LdapException;
use Ohffs\Ldap\FakeLdapConnection;

class LdapTest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    public function can_authenticate_a_user_in_ldap()
    {
        $connection = new FakeLdapConnection;
        $ldap = new LdapService($connection);

        $this->assertTrue($ldap->authenticate('validuser', 'validpassword'));
    }

    /** @test */
    public function a_valid_user_but_wrong_password_doesnt_authenticate()
    {
        $connection = new FakeLdapConnection;
        $ldap = new LdapService($connection);

        $this->assertFalse($ldap->authenticate('validuser', 'invalidpassword'));
    }

    /** @test */
    public function an_invalid_user_doesnt_authenticate()
    {
        $connection = new FakeLdapConnection;
        $ldap = new LdapService($connection);

        $this->assertFalse($ldap->authenticate('invaliduser', 'invalidpassword'));
    }

    /** @test */
    public function if_cannot_connect_to_server_an_exception_is_thrown()
    {
        $connection = new FakeLdapConnection;
        $ldap = new LdapService($connection);

        try {
            $this->assertFalse($ldap->authenticate('serverdown', 'invalidpassword'));
        } catch (LdapException $e) {
            return $this->assertTrue(true);
        }
        $this->fail("Expected an LdapException but none was thrown");
    }

    /** @test */
    public function can_use_the_facade()
    {
        $this->assertTrue(\Ldap::authenticate('validuser', 'validpassword'));
    }

    protected function getPackageProviders($app)
    {
        return [
            Ohffs\Ldap\LdapServiceProvider::class,
            Ohffs\Ldap\LdapConnectionProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Ldap' => 'Ohffs\Ldap\LdapFacade'
        ];
    }
}