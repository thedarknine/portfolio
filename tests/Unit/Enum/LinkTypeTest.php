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
        $this->assertSame('Lien interne', LinkType::INTERNAL->getLabel());
        $this->assertSame('Détail', LinkType::DETAIL->getLabel());
    }

    /**
     * Test if returned FontAwesome classes match each case.
     */
    public function testGetIconReturnsCorrectFontAwesomeClass(): void
    {
        $this->assertSame('fa7-solid:file-word', LinkType::DOCUMENT->getIcon());

        // Valid that PDF keeps its color class
        $this->assertSame('flowbite:file-pdf-outline text-danger', LinkType::PDF->getIcon());

        $this->assertSame('fa7-solid:globe-europe', LinkType::EXTERNAL->getIcon());
        $this->assertSame('fa7-solid:link', LinkType::INTERNAL->getIcon());
        $this->assertSame('fa7-solid:eye', LinkType::DETAIL->getIcon());
    }

    /**
     * Test if enum values are consistent (important if they are stored in database).
     */
    public function testEnumValuesAreConsistent(): void
    {
        $this->assertSame('document', LinkType::DOCUMENT->value);
        $this->assertSame('pdf', LinkType::PDF->value);
        $this->assertSame('external', LinkType::EXTERNAL->value);
        $this->assertSame('internal', LinkType::INTERNAL->value);
        $this->assertSame('detail', LinkType::DETAIL->value);
    }
}
