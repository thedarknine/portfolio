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

        $io->title('Création d\'un utilisateur Administrateur');

        $email = $io->ask('Entrez l\'adresse e-mail', null, function ($email) {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('L\'adresse e-mail n\'est pas valide.');
            }

            return $email;
        });

        $password = $io->askHidden('Entrez le mot de passe', function ($password) {
            if (empty($password) || strlen($password) < 6) {
                throw new \RuntimeException('Le mot de passe doit faire au moins 6 caractères.');
            }

            return $password;
        });

        // Check if a user with the same email already exists
        $userRepository = $this->entityManager->getRepository(User::class);
        if ($userRepository->findOneBy(['email' => $email])) {
            $io->error(sprintf('L\'utilisateur %s existe déjà en base de données !', $email));

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

        $io->success(sprintf('L\'administrateur %s a été créé avec succès !', $email));

        return Command::SUCCESS;
    }
}
