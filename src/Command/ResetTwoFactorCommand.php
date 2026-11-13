<?php

declare(strict_types=1);

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
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:reset-2fa',
    description: 'Generate a new two-factor (TOTP) secret for an administrator, or disable it.',
)]
class ResetTwoFactorCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly GoogleAuthenticatorInterface $googleAuthenticator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address of the administrator')
            ->addOption('disable', null, InputOption::VALUE_NONE, 'Remove the secret instead of generating a new one');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io      = new SymfonyStyle($input, $output);
        $email   = (string) $input->getArgument('email');
        $disable = (bool) $input->getOption('disable');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user instanceof User) {
            $io->error(sprintf('No user found for %s.', $email));

            return Command::FAILURE;
        }

        $question = $disable
            ? sprintf('Disable two-factor authentication for %s?', $email)
            : sprintf('Replace the two-factor secret of %s? Codes from the current authenticator entry will stop working.', $email);
        if (!$io->confirm($question, false)) {
            $io->note('Nothing was changed.');

            return Command::SUCCESS;
        }

        if ($disable) {
            $user->setGoogleAuthenticatorSecret(null);
            $this->entityManager->flush();
            $io->warning(sprintf('Two-factor authentication disabled for %s.', $email));

            return Command::SUCCESS;
        }

        $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
        $this->entityManager->flush();

        $io->success(sprintf('New two-factor secret generated for %s.', $email));
        $io->text([
            'Add it to your authenticator app, then delete the old entry:',
            '',
            sprintf('  Setup key (time-based): <info>%s</info>', $user->getGoogleAuthenticatorSecret()),
            '',
            'Or turn this URI into a QR code, e.g. on macOS: qrencode -t ansiutf8 \'<uri>\'',
            '',
            sprintf('  %s', $this->googleAuthenticator->getQRContent($user)),
            '',
        ]);
        $io->caution('Clear the terminal once done: the secret is displayed in clear.');

        return Command::SUCCESS;
    }
}
