<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\ArcadeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArcadeType>
 */
class ArcadeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArcadeType::class);
    }

    /**
     * @return ArcadeType[] Returns an array of ArcadeType objects
     */
    public function getArcadeTypes(): array
    {
        return $this->createQueryBuilder('arc')
            ->select('arc')
            ->orderBy('arc.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
