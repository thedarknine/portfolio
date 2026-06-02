<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Command;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;

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

    /**
     * Test admin user creation via console command.
     */
    public function testExecuteCreatesUserSuccessfully(): void
    {
        $kernel      = self::$kernel;
        $application = new Application($kernel);

        // 1. Get the command registered in the Symfony application
        $command       = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        // 2. Simulate user input in the terminal
        // Input 1: the email address, Input 2: the password (min 6 chars)
        $commandTester->setInputs([
            'admin@portfolio.fr',
            'super_password_123',
        ]);

        // 3. Execute the command
        $statusCode = $commandTester->execute([]);

        // 4. Assertions on the command result
        $this->assertSame(0, $statusCode, 'The command should return Command::SUCCESS (0)');

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Administrator admin@portfolio.fr has been created successfully', $output);

        // 5. Validation in Database
        $userRepository = $this->entityManager->getRepository(User::class);
        $user           = $userRepository->findOneBy(['email' => 'admin@portfolio.fr']);

        $this->assertNotNull($user);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        // Ensure the password is not stored in plain text
        $this->assertNotSame('super_password_123', $user->getPassword());
    }

    /**
     * Test that email validation rejects invalid formats.
     */
    public function testExecuteValidatesEmailFormatInteractively(): void
    {
        $kernel        = self::$kernel;
        $application   = new Application($kernel);
        $command       = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        // Simulate a user who first enters an invalid value,
        // then a valid value for the email, and finally the password.
        $commandTester->setInputs([
            'invalid-email-format',    // 1st attempt (invalid -> will trigger the exception caught by SymfonyStyle)
            'valid-admin@portfolio.fr', // 2nd attempt (valid -> the command continues)
            'secure_password_123',      // Password entry
        ]);

        $statusCode = $commandTester->execute([]);

        // The command must still succeed at the end since the second input was valid
        $this->assertSame(0, $statusCode);

        // Check that the error message from your RuntimeException was displayed in the terminal
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Email address is not valid.', $output);
        $this->assertStringContainsString('Administrator valid-admin@portfolio.fr has been created successfully', $output);
    }

    /**
     * Test that password validation rejects strings that are too short.
     */
    public function testExecuteValidatesPasswordLengthInteractively(): void
    {
        $kernel        = self::$kernel;
        $application   = new Application($kernel);
        $command       = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs([
            'admin-test@portfolio.fr', // Valid email on first try
            '123',                     // 1st password attempt (too short -> triggers the error)
            'password_valide_999',     // 2nd password attempt (valid)
        ]);

        $statusCode = $commandTester->execute([]);

        $this->assertSame(0, $statusCode);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Password must be at least 6 characters long.', $output);
    }

    /**
     * Test that the command fails properly if the email already exists.
     */
    public function testExecuteFailsIfUserAlreadyExists(): void
    {
        // 1. Insert an existing user in the database
        $existingUser = (new User())
            ->setEmail('double@portfolio.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('any_hash');

        $this->entityManager->persist($existingUser);
        $this->entityManager->flush();

        $kernel        = self::$kernel;
        $application   = new Application($kernel);
        $command       = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        // 2. Provide the same information for the command
        $commandTester->setInputs([
            'double@portfolio.fr',
            'another_password_678',
        ]);

        $statusCode = $commandTester->execute([]);

        // 3. Assertions on the expected failure
        $this->assertSame(1, $statusCode, 'The command should return Command::FAILURE (1)');

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User double@portfolio.fr already exists in the database', $output);
    }

    /**
     * Clean up the user table after each test.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM user');
        $this->entityManager->clear();
    }
}
