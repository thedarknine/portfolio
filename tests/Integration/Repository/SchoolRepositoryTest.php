<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\School;
use App\Repository\SchoolRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SchoolRepositoryTest extends KernelTestCase
{
    public function testRepositoryIsInstantiable(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get('doctrine.orm.entity_manager')->getRepository(School::class);

        $this->assertInstanceOf(SchoolRepository::class, $repository);
    }
}
