<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Controller;

use App\Controller\SeoController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class SeoControllerTest extends TestCase
{
    /**
     * This test bypasses the HttpKernel to directly test the controller's response.
     * ResponseListener is not triggered, so the Content-Type is not set by Symfony.
     */
    public function testRobotsTxtSetsExplicitPlainTextContentType(): void
    {
        $controller = $this->createControllerForEnvironment('test');

        $response = $controller->robotsTxt();

        $this->assertSame('text/plain', $response->headers->get('Content-Type'));
    }

    public function testRobotsTxtBlocksEverythingOutsideProduction(): void
    {
        $controller = $this->createControllerForEnvironment('test');

        $response = $controller->robotsTxt();

        $this->assertSame("User-agent: *\nDisallow: /", $response->getContent());
    }

    public function testRobotsTxtAllowsCrawlingInProduction(): void
    {
        $controller = $this->createControllerForEnvironment('prod');

        $response = $controller->robotsTxt();

        $this->assertStringContainsString('Allow: /', $response->getContent());
        $this->assertStringContainsString('Disallow: /admin/', $response->getContent());
    }

    private function createControllerForEnvironment(string $environment): SeoController
    {
        $controller = new SeoController();

        // Build a minimal container with only what AbstractController::getParameter() needs:
        // a 'parameter_bag' service exposing kernel.environment.
        $container = new Container();
        $container->setParameter('kernel.environment', $environment);
        $container->set('parameter_bag', $container->getParameterBag());

        $controller->setContainer($container);

        return $controller;
    }
}
