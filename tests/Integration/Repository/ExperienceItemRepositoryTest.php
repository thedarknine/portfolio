<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\ExperienceItem;
use App\Repository\ExperienceItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExperienceItemRepositoryTest extends KernelTestCase
{
    public function testRepositoryIsInstantiable(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get('doctrine.orm.entity_manager')->getRepository(ExperienceItem::class);

        $this->assertInstanceOf(ExperienceItemRepository::class, $repository);
    }
}
