<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\CreationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreationType>
 */
class CreationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreationType::class);
    }

    /**
     * @return CreationType[] Returns an array of CreationType objects
     */
    public function getCreationTypes(): array
    {
        return $this->createQueryBuilder('crea')
            ->select('crea')
            ->orderBy('crea.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
