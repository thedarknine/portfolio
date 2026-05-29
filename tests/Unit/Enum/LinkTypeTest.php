<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Enum;

use App\Enum\LinkType;
use PHPUnit\Framework\TestCase;

class LinkTypeTest extends TestCase
{
    /**
     * Test if returned labels match each case.
     */
    public function testGetLabelReturnsCorrectString(): void
    {
        $this->assertSame('Document', LinkType::DOCUMENT->getLabel());
        $this->assertSame('Fichier PDF', LinkType::PDF->getLabel());
        $this->assertSame('Lien externe', LinkType::EXTERNAL->getLabel());
    }

    /**
     * Test if returned FontAwesome classes match each case.
     */
    public function testGetIconReturnsCorrectFontAwesomeClass(): void
    {
        $this->assertSame('fa-file-word', LinkType::DOCUMENT->getIcon());

        // Valid that PDF keeps its color class
        $this->assertSame('fa-file-pdf text-danger', LinkType::PDF->getIcon());

        $this->assertSame('fa-external-link-alt', LinkType::EXTERNAL->getIcon());
    }

    /**
     * Test if enum values are consistent (important if they are stored in database).
     */
    public function testEnumValuesAreConsistent(): void
    {
        $this->assertSame('document', LinkType::DOCUMENT->value);
        $this->assertSame('pdf', LinkType::PDF->value);
        $this->assertSame('external', LinkType::EXTERNAL->value);
    }
}
