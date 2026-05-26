<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $this->ensureTestUserExists();
    }

    /**
     * A user already logged in who goes to /login should be redirected
     * to the home page or dashboard depending on logic.
     */
    public function testLoginRedirectsWhenUserIsAlreadyLoggedIn(): void
    {
        // 1. Get our test user
        $userRepository = $this->entityManager->getRepository(User::class);
        $testUser = $userRepository->findOneBy(['email' => 'studio@carolinenoyer.fr']);

        // 2. Connect the user on the client
        $this->client->loginUser($testUser);

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
        $testUser = $userRepository->findOneBy(['email' => 'studio@carolinenoyer.fr']);
        $this->client->loginUser($testUser);

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
        $controller = new \App\Controller\SecurityController();

        // 2. Expect the LogicException from Symfony to be thrown
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('This method can be blank - it will be intercepted by the logout key on your firewall.');

        // 3. Call the method directly
        $controller->logout();
    }

    /**
     * Ensure the presence of a test user in the database.
     */
    private function ensureTestUserExists(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        if (null === $userRepository->findOneBy(['email' => 'studio@carolinenoyer.fr'])) {
            $user = (new User())
                ->setEmail('studio@carolinenoyer.fr')
                ->setPassword('$2y$13$dummyhashedpasswordstrings') // Simulation d'un hash valide
                ->setRoles(['ROLE_ADMIN']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
