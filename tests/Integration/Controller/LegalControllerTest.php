<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LegalControllerTest extends WebTestCase
{
    public function testLegalPageReturnsResponse(): void
    {
        $client = static::createClient();
        $client->request('GET', '/mentions-legales');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mentions légales');
    }
}
