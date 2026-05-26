<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\ExperienceLink;
use App\Repository\ExperienceLinkRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExperienceLinkRepositoryTest extends KernelTestCase
{
    public function testRepositoryIsInstantiable(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get('doctrine.orm.entity_manager')->getRepository(ExperienceLink::class);

        $this->assertInstanceOf(ExperienceLinkRepository::class, $repository);
    }
}
