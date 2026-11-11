<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SeoControllerTest extends WebTestCase
{
    public function testRobotsTxtReturnsResponse(): void
    {
        $client = static::createClient();

        $client->request('GET', '/robots.txt');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'text/plain; charset=UTF-8');
    }

    public function testRobotsTxtContentInDev(): void
    {
        $client = static::createClient();

        $client->request('GET', '/robots.txt');

        $content = $client->getResponse()->getContent();

        self::assertStringContainsString('User-agent: *', $content);
        self::assertStringContainsString('Disallow: /', $content);
    }

    public function testRobotsTxtBlocksEverythingOutsideProduction(): void
    {
        // 'test' !== 'prod' → on doit obtenir le contenu "Disallow: /"
        $client = static::createClient(['environment' => 'test']);
        $client->request('GET', '/robots.txt');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertSame("User-agent: *\nDisallow: /", $response->getContent());
        $this->assertSame('text/plain; charset=UTF-8', $response->headers->get('Content-Type'));
    }
}
