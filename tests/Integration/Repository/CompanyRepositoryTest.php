<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompanyRepositoryTest extends KernelTestCase
{
    public function testRepositoryIsInstantiable(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get('doctrine.orm.entity_manager')->getRepository(Company::class);

        $this->assertInstanceOf(CompanyRepository::class, $repository);
    }
}
