<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Repository;

use App\Entity\PhotoType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotoType>
 */
class PhotoTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoType::class);
    }

    /**
     * @return PhotoType[] Returns an array of PhotoType objects
     */
    public function getPhotoTypes(): array
    {
        return $this->createQueryBuilder('phot')
            ->select('phot')
            ->orderBy('phot.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
