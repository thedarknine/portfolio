<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new administrator user for the admin dashboard.',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Admin User Creation');

        $email = $io->ask('Enter the email address', null, function ($email) {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Email address is not valid.');
            }

            return $email;
        });

        $password = $io->askHidden('Enter the password', function ($password) {
            if (empty($password) || strlen($password) < 6) {
                throw new \RuntimeException('Password must be at least 6 characters long.');
            }

            return $password;
        });

        // Check if a user with the same email already exists
        $userRepository = $this->entityManager->getRepository(User::class);
        if ($userRepository->findOneBy(['email' => $email])) {
            $io->error(sprintf('User %s already exists in the database!', $email));

            return Command::FAILURE;
        }

        // Create and populate the User entity
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']); // Set the admin role

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // Persist the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Administrator %s has been created successfully!', $email));

        return Command::SUCCESS;
    }
}
