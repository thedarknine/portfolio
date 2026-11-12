<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:reset-password',
    description: 'Set a new password for an existing administrator.',
)]
class ResetPasswordCommand extends Command
{
    private const int MIN_LENGTH = 12;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email address of the administrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io    = new SymfonyStyle($input, $output);
        $email = (string) $input->getArgument('email');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user instanceof User) {
            $io->error(sprintf('No user found for %s.', $email));

            return Command::FAILURE;
        }

        // The password is read hidden, so it never shows on screen nor in the shell history
        $password = $io->askHidden('New password', static function (?string $password): string {
            if (null === $password || mb_strlen($password) < self::MIN_LENGTH) {
                throw new \RuntimeException(sprintf('Password must be at least %d characters long.', self::MIN_LENGTH));
            }

            return $password;
        });

        $confirmation = $io->askHidden('Repeat the new password');
        if ($confirmation !== $password) {
            $io->error('The two passwords do not match, nothing was changed.');

            return Command::FAILURE;
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->entityManager->flush();

        $io->success(sprintf('Password of %s updated. Existing sessions are logged out at their next request.', $email));

        return Command::SUCCESS;
    }
}
