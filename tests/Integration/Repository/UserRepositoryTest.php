<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager  = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->cleanUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Test that the method successfully updates the password.
     */
    public function testUpgradePasswordSuccessfully(): void
    {
        // 1. Creating a user with an initial password
        $user = (new User())
            ->setEmail('dev@example.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('old_hash_123');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // 2. Calling upgradePassword to modify the hash
        $this->userRepository->upgradePassword($user, 'new_secure_hash_456');

        // Forcing Doctrine to refresh to verify what is really in the database
        $this->entityManager->clear();
        $updatedUser = $this->userRepository->findOneBy(['email' => 'dev@example.com']);

        // 3. Assertions
        $this->assertNotNull($updatedUser);
        $this->assertSame('new_secure_hash_456', $updatedUser->getPassword(), 'The password should have been updated in the database.');
    }

    /**
     * Test that the method throws an exception if the user instance is not supported.
     */
    public function testUpgradePasswordThrowsExceptionOnUnsupportedUser(): void
    {
        // Using createStub() instead of createMock() to please PHPUnit
        $unsupportedUser = $this->createStub(PasswordAuthenticatedUserInterface::class);

        // Expecting the specific Symfony exception
        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessageMatches('/Instances of ".*" are not supported\./');

        // Calling the method with the fake user
        $this->userRepository->upgradePassword($unsupportedUser, 'some_hash');
    }

    /**
     * Clean up the user table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM user');
        $this->entityManager->clear();
    }
}
