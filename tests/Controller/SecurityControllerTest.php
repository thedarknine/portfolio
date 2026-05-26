<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * Anonymous user should be redirected when trying to access admin.
     */
    public function testAdminZoneIsProtected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        // Will be redirected (302) to login page
        $this->assertResponseRedirects();
    }

    /**
     * Login page should be accessible.
     */
    public function testLoginPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        // Check that the form is present
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
    }

    /**
     * Check form submission with valid credentials.
     */
    public function testLoginSubmitWithValidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Select the form button (adjust the text according to your button)
        $form = $crawler->filter('form')->form([
            '_username' => 'admin@carolinenoyer.fr', // An email from your fixtures
            '_password' => 'password_test',          // The corresponding password
        ]);

        $client->submit($form);
        // After a successful connection, Symfony redirects the user
        $this->assertResponseRedirects('/admin');

        // Follow the redirect to ensure the destination page works
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCanAccessDashboard(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $adminUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@carolinenoyer.fr']);
        $client->loginUser($adminUser);

        // Instead of: $client->request('GET', '/admin');
        // We use the router to generate the real EasyAdmin route
        $router = $container->get('router');

        // Replace 'admin' with your route name if you customized it
        $url = $router->generate('admin');

        $client->request('GET', $url);
        $this->assertResponseIsSuccessful();
    }
}
