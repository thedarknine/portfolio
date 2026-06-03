<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeoController extends AbstractController
{
    #[Route('/robots.txt', name: 'app_robots_txt', defaults: ['_format' => 'txt'])]
    public function robotsTxt(): Response
    {
        $env = $this->getParameter('kernel.environment');

        // From dev/staging block everything to avoid duplicate content
        if ('prod' !== $env) {
            $content = "User-agent: *\nDisallow: /";
        } else {
            // In production, open and protect admin and 2FA
            $content = "User-agent: *\n";
            $content .= "Allow: /\n";
            $content .= "Disallow: /admin/\n";
            $content .= "Disallow: /backstage/\n";
            $content .= "Disallow: /login\n\n";
            $content .= "Disallow: /2fa/\n";
            $content .= "Disallow: /2fa_check/\n";
            $content .= 'Sitemap: https://www.carolinenoyer.fr/sitemap.xml';
        }

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }
}
