<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ProfileController extends AbstractController
{
    #[Route('/backstage/profile', name: 'admin_profile')]
    public function index(
        GoogleAuthenticatorInterface $googleAuthenticator,
        EntityManagerInterface $entityManager,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        // 1. Si l'utilisateur n'a pas encore de secret, on en génère un
        if (!$user->getGoogleAuthenticatorSecret()) {
            $user->setGoogleAuthenticatorSecret($googleAuthenticator->generateSecret());
            $entityManager->flush();
        }

        // 2. On récupère la clé secrète textuelle brute
        $secretKey = $user->getGoogleAuthenticatorSecret();

        // 3. Optionnel : On formate la clé par blocs de 4 lettres pour la rendre lisible
        $formattedSecret = chunk_split((string) $secretKey, 4, ' ');

        return $this->render('admin/profile.html.twig', [
            'secretKey' => $formattedSecret,
            'user'      => $user,
        ]);
    }
}
