<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Education;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Education>
 */
class EducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }

    /**
     * @return Education[] Returns an array of Education objects
     */
    public function getEducationsWithSchool(?string $schoolType = null): array
    {
        $query = $this->createQueryBuilder('educ')
            ->select('educ')
            ->innerJoin('educ.school', 'sch')
            ->addSelect('sch');

        if ($schoolType) {
            $query->where('educ.type = :schoolType')
                  ->setParameter('schoolType', $schoolType);
        }

        return $query->orderBy('educ.year', 'DESC')
                     ->getQuery()
                     ->getResult();
    }
}
