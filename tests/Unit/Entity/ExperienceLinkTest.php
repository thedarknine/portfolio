<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\ExperienceLink;
use PHPUnit\Framework\TestCase;

class ExperienceLinkTest extends TestCase
{
    public function testSetUrlNormalizesWhitespaceOnlyStringToNull(): void
    {
        $link = new ExperienceLink();
        $link->setUrl('   '); // espaces uniquement, pas une vraie chaîne vide

        $this->assertNull($link->getUrl());
    }

    public function testSetUrlKeepsValidUrl(): void
    {
        $link = new ExperienceLink();
        $link->setUrl('https://example.com');

        $this->assertSame('https://example.com', $link->getUrl());
    }

    public function testSetUrlNormalizesEmptyStringToNull(): void
    {
        $link = new ExperienceLink();
        $link->setUrl('');

        $this->assertNull($link->getUrl());
    }
}
