<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Controller;

use App\Repository\PageInfoRepository;
use App\Repository\ResourceLinkRepository;
use Doctrine\DBAL\Exception as DatabaseException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders the Twig error templates directly. Going through a request would need a second kernel without debug
 * (the debug kernel shows the exception page instead), and compiling a second container in the same PHP process
 * exhausts the memory limit of the test suite.
 */
class ErrorPageTest extends KernelTestCase
{
    public function testNotFoundPageUsesTheErrorLayout(): void
    {
        $crawler = $this->renderErrorPage('error404', Response::HTTP_NOT_FOUND, '/page-qui-n-existe-pas');

        $this->assertSame('Cette page s’est perdue en chemin.', trim($crawler->filter('h1')->text()));
        $this->assertStringContainsString('cd ~/page-qui-n-existe-pas', $crawler->filter('figure')->text());
        $this->assertCount(1, $crawler->filter('nav.site-nav a[href="/experience"]'));
        $this->assertGreaterThan(0, $crawler->filter('a[href="/projets"]')->count());
        $this->assertCount(1, $crawler->filter('meta[name="robots"][content="noindex"]'));
    }

    public function testServerErrorPageRendersWhenTheDatabaseIsDown(): void
    {
        $databaseDown = new class('Connection refused') extends \RuntimeException implements DatabaseException {
        };

        $pageInfoRepository = $this->createStub(PageInfoRepository::class);
        $pageInfoRepository->method('findRootPages')->willThrowException($databaseDown);
        $pageInfoRepository->method('findPublishedRootPagesInHeader')->willThrowException($databaseDown);
        $pageInfoRepository->method('findAllAsArray')->willThrowException($databaseDown);
        $resourceLinkRepository = $this->createStub(ResourceLinkRepository::class);
        $resourceLinkRepository->method('findAllAsArray')->willThrowException($databaseDown);
        static::getContainer()->set(PageInfoRepository::class, $pageInfoRepository);
        static::getContainer()->set(ResourceLinkRepository::class, $resourceLinkRepository);

        $crawler = $this->renderErrorPage('error', Response::HTTP_INTERNAL_SERVER_ERROR, '/experience');

        $this->assertSame('Le serveur a perdu le fil.', trim($crawler->filter('h1')->text()));
        // Navigation comes from App\Service\FallbackNavigation
        $this->assertCount(1, $crawler->filter('nav.site-nav a[href="/experience"]'));
        $this->assertCount(1, $crawler->filter('footer a[aria-label="GitHub"]'));
    }

    private function renderErrorPage(string $template, int $statusCode, string $path): Crawler
    {
        $container = static::getContainer();
        $container->get('request_stack')->push(Request::create($path));

        $html = $container->get('twig')->render("bundles/TwigBundle/Exception/{$template}.html.twig", [
            'status_code' => $statusCode,
            'status_text' => Response::$statusTexts[$statusCode],
            'exception'   => FlattenException::createFromThrowable(new \RuntimeException(), $statusCode),
        ]);

        return new Crawler($html, 'http://localhost' . $path);
    }
}
