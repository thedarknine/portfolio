<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client        = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $this->createAdminUser();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    /**
     * Anonymous user should be redirected when trying to access admin.
     */
    public function testAdminZoneIsProtected(): void
    {
        $this->client->request('GET', '/backstage');

        // Will be redirected (302) to login page
        $this->assertResponseRedirects();
    }

    /**
     * Login page should be accessible.
     */
    public function testLoginPageIsSuccessful(): void
    {
        $this->client->request('GET', '/login');

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
        $crawler = $this->client->request('GET', '/login');

        $container     = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $adminUser     = $entityManager->getRepository(User::class)->findOneBy(['email' => 'studiotest@carolinenoyer.fr']);

        // Select the form button (adjust the text according to your button)
        $form = $crawler->filter('form')->form([
            '_username' => $adminUser->getEmail(),
            '_password' => '$2y$13$dummyhashedpasswordstrings',
        ]);

        $this->client->submit($form);
        // After a successful connection, Symfony redirects the user
        $this->assertResponseRedirects('/backstage');

        // Follow the redirect to ensure the destination page works
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCanAccessDashboard(): void
    {
        $container     = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $adminUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'studiotest@carolinenoyer.fr']);
        $this->client->loginUser($adminUser);

        // Instead of: $client->request('GET', '/backstage');
        // We use the router to generate the real EasyAdmin route
        $router = $container->get('router');

        // Replace 'admin' with your route name if you customized it
        $url = $router->generate('admin');

        $this->client->request('GET', $url);
        $this->assertResponseIsSuccessful();
    }

    /**
     * A user already logged in who goes to /login should be redirected
     * to the home page or dashboard depending on logic.
     */
    public function testLoginRedirectsWhenUserIsAlreadyLoggedIn(): void
    {
        // 1. Get our test user
        $userRepository = $this->entityManager->getRepository(User::class);
        $admin          = $userRepository->findOneBy(['email' => 'studiotest@carolinenoyer.fr']);

        // 2. Connect the user on the client
        $this->client->loginUser($admin);

        // 3. Try to access the login page
        $this->client->request('GET', '/login');

        // 4. Check that we are redirected (HTTP 302)
        $this->assertResponseStatusCodeSame(302);

        // Optional: You can check the redirect target if you know it (e.g., /)
        // $this->assertResponseRedirects('/');
    }

    /**
     * Test the logout method
     * The logout method in your controller is usually empty because it's intercepted by the firewall.
     * Calling the route allows you to verify that it doesn't throw an error and initiates the disconnection.
     */
    public function testLogoutRouteInitiatesDisconnection(): void
    {
        // 1. Connect
        $userRepository = $this->entityManager->getRepository(User::class);
        $admin          = $userRepository->findOneBy(['email' => 'studiotest@carolinenoyer.fr']);
        $this->client->loginUser($admin);

        // 2. Call the logout route (usually /logout)
        $this->client->request('GET', '/logout');

        // 3. The Symfony Security component must intercept the request and redirect (HTTP 302)
        $this->assertResponseStatusCodeSame(302);
    }

    /**
     * Force the coverage of the logout method by calling it directly.
     */
    public function testLogoutMethodThrowsLogicException(): void
    {
        // 1. Instantiate the controller directly (without going through the Kernel)
        $container  = static::getContainer();
        $controller = $container->get(\App\Controller\SecurityController::class);

        // 2. Expect the LogicException from Symfony to be thrown
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('This method can be blank - it will be intercepted by the logout key on your firewall.');

        // 3. Call the method directly
        $controller->logout();
    }

    /**
     * Ensure the presence of an admin user in the database.
     */
    private function createAdminUser(): void
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $admin = (new User())
            ->setEmail('studiotest@carolinenoyer.fr')
            ->setRoles(['ROLE_ADMIN']);

        $admin->setPassword($passwordHasher->hashPassword($admin, '$2y$13$dummyhashedpasswordstrings'));

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }
}
