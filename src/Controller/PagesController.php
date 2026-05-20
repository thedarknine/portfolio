<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PagesController extends AbstractController
{
    private function getNbYearsExperience(): int
    {
        $startDate = new \DateTime('2006-10-01');
        $currentDate = new \DateTime();

        return $startDate->diff($currentDate)->y;
    }

    private function getImagesDir(): string
    {
        return $this->getParameter('kernel.project_dir').'/public/images/';
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $data['nbYearsExperience'] = $this->getNbYearsExperience();
        $data['mentalLandscape'] = [
            'rugby' => ['image' => 'images/home/rugby-asm.jpeg', 'alt' => 'Rugby', 'big' => false],
            'zen' => ['image' => 'images/home/lac-zen.jpg', 'alt' => 'Sérénité', 'big' => true],
            'chat' => ['image' => 'images/home/chat.jpg', 'alt' => 'Chat', 'big' => false],
            'auvergne' => ['image' => 'images/home/auvergne.jpg', 'alt' => 'Auvergne', 'big' => false],
            'equipe' => ['image' => 'images/home/equipe.jpg', 'alt' => 'Equipe', 'big' => false],
        ];

        return $this->render('pages/home.html.twig', [
            'page' => 'home',
            'data' => $data,
        ]);
    }
}
