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

class ResetTwoFactorCommandTest extends KernelTestCase
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

    public function testExecuteGeneratesANewSecret(): void
    {
        $this->createUser('admin@portfolio.fr', 'OLDSECRETOLDSECRET');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['yes']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr']);

        $this->assertSame(0, $statusCode);
        $secret = $this->findUser('admin@portfolio.fr')->getGoogleAuthenticatorSecret();
        $this->assertNotNull($secret);
        $this->assertNotSame('OLDSECRETOLDSECRET', $secret);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString($secret, $output);
        $this->assertStringContainsString('otpauth://totp/', $output);
    }

    public function testExecuteKeepsTheSecretWhenNotConfirmed(): void
    {
        $this->createUser('admin@portfolio.fr', 'OLDSECRETOLDSECRET');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['no']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr']);

        $this->assertSame(0, $statusCode);
        $this->assertStringContainsString('Nothing was changed', $commandTester->getDisplay());
        $this->assertSame('OLDSECRETOLDSECRET', $this->findUser('admin@portfolio.fr')->getGoogleAuthenticatorSecret());
    }

    public function testExecuteDisablesTwoFactor(): void
    {
        $this->createUser('admin@portfolio.fr', 'OLDSECRETOLDSECRET');

        $commandTester = $this->commandTester();
        $commandTester->setInputs(['yes']);
        $statusCode = $commandTester->execute(['email' => 'admin@portfolio.fr', '--disable' => true]);

        $this->assertSame(0, $statusCode);
        $this->assertNull($this->findUser('admin@portfolio.fr')->getGoogleAuthenticatorSecret());
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

        return new CommandTester($application->find('app:user:reset-2fa'));
    }

    private function createUser(string $email, string $secret): void
    {
        $user = new User()
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('any_hash')
            ->setGoogleAuthenticatorSecret($secret);

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
