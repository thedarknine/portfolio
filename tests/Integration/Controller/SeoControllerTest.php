<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
}
