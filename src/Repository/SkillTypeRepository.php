<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\SkillType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkillType>
 */
class SkillTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkillType::class);
    }

    /**
     * @return SkillType[] Returns an array of SkillType objects
     */
    public function getSkillTypes(): array
    {
        return $this->createQueryBuilder('sklt')
            ->select('sklt')
            ->andWhere('sklt.deleted = 0')
            ->orderBy('sklt.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
