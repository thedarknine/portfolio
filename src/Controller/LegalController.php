<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Service\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    public function __construct(private PageService $pageService)
    {
    }

    #[Route('/mentions-legales', name: 'app_legal', options: ['sitemap' => true])]
    public function legal(): Response
    {
        $page = $this->pageService->getActivePageBySlug('mentions-legales');

        return $this->render('pages/legal.html.twig', [
            'current_page' => $page,
        ]);
    }
}
