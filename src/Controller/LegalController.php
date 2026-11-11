<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Controller;

use App\Service\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    public function __construct(private readonly PageService $pageService)
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
