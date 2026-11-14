<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\PageInfo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name: 'app_home', options: ['sitemap' => true])]
    public function index(): Response
    {
        // An unpublished "home" page in the database carries the Open Graph texts and dates of the home page,
        // without appearing in the navigation (only published pages are listed)
        $data['current_page'] = $this->doctrine->getRepository(PageInfo::class)->findOneBy(['technicalName' => 'home'])
            ?? [
                'title'         => 'Accueil',
                'slug'          => 'home',
                'technicalName' => 'home',
            ];
        $data['last_experiences'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(3);

        return $this->render('pages/home.html.twig', [
            'page' => 'home',
            ...$data,
        ]);
    }
}
