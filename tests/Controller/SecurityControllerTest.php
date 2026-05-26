<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * Anonymous user should be redirected when trying to access admin
     */
    public function testAdminZoneIsProtected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        // Will be redirected (302) to login page
        $this->assertResponseRedirects();
    }

    /**
     * Login page should be accessible
     */
    public function testLoginPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login'); 

        $this->assertResponseIsSuccessful();
        // Check that the form is present
        $this->assertSelectorExists('form');
    }
}
