<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Experience;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name: 'app_home', options: ['sitemap' => true])]
    public function index(): Response
    {
        $data['current_page'] = [
            'title'         => 'Accueil',
            'slug'          => 'home',
            'technicalName' => 'home',
        ];
        $data['mental_landscape'] = [
            'rugby'    => ['image' => 'images/home/rugby-asm.webp', 'alt' => 'Équipe de rugby en discussion, symbolisant l\'esprit d\'équipe', 'big' => false],
            'zen'      => ['image' => 'images/home/lac-zen.webp', 'alt' => 'Ponton en bois sur un lac calme, représentant la clarté et la structure', 'big' => true],
            'chat'     => ['image' => 'images/home/chat.webp', 'alt' => 'Chat endormi sur un coussin, illustrant l\'équilibre et le bien-être', 'big' => false],
            'auvergne' => ['image' => 'images/home/auvergne.webp', 'alt' => 'Paysage volcanique d\'Auvergne sous un ciel dégagé, symbolisant la vision globale et la réflexion stratégique', 'big' => false],
            'equipe'   => ['image' => 'images/home/equipe.webp', 'alt' => 'Équipe de développeurs en collaboration, illustrant le pragmatisme, l\'organisation et l\'approche méthodique', 'big' => false],
        ];
        $data['last_experiences'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(3);

        return $this->render('pages/home.html.twig', [
            'page' => 'home',
            ...$data,
        ]);
    }
}
