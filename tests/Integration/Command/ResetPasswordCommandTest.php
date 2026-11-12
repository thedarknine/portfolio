<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Integration\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordCommandTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->cleanUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testExecuteUpdatesThePassword(): void
    {
        $this->createUser('admin@portfolio.fr');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['new_password_1234', 'new_password_1234']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr']);

        $this->assertSame(0, $statusCode);
        $this->assertStringContainsString('Password of admin@portfolio.fr updated', $commandTester->getDisplay());

        $user = $this->findUser('admin@portfolio.fr');
        /** @var UserPasswordHasherInterface $hasher */
        $hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $this->assertTrue($hasher->isPasswordValid($user, 'new_password_1234'));
    }

    public function testExecuteRejectsShortPasswordsInteractively(): void
    {
        $this->createUser('admin@portfolio.fr');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['short', 'new_password_1234', 'new_password_1234']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr']);

        $this->assertSame(0, $statusCode);
        $this->assertStringContainsString('Password must be at least 12 characters long.', $commandTester->getDisplay());
    }

    public function testExecuteFailsWhenPasswordsDoNotMatch(): void
    {
        $this->createUser('admin@portfolio.fr');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['new_password_1234', 'other_password_1234']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr']);

        $this->assertSame(1, $statusCode);
        $this->assertStringContainsString('do not match', $commandTester->getDisplay());
        $this->assertSame('old_hash', $this->findUser('admin@portfolio.fr')->getPassword());
    }

    public function testExecuteFailsForUnknownUser(): void
    {
        $commandTester = $this->commandTester();
        $statusCode    = $commandTester->execute(['email' => 'nobody@portfolio.fr']);

        $this->assertSame(1, $statusCode);
        $this->assertStringContainsString('No user found for nobody@portfolio.fr', $commandTester->getDisplay());
    }

    private function commandTester(): CommandTester
    {
        $application = new Application(self::$kernel);

        return new CommandTester($application->find('app:user:reset-password'));
    }

    private function createUser(string $email): void
    {
        $user = new User()
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('old_hash');

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function findUser(string $email): User
    {
        $this->entityManager->clear();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->assertInstanceOf(User::class, $user);

        return $user;
    }

    private function cleanUpDatabase(): void
    {
        $this->entityManager->getConnection()->executeStatement('DELETE FROM user');
        $this->entityManager->clear();
    }
}
