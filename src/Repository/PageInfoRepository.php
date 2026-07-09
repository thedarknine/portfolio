<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\PageInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageInfo>
 */
class PageInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageInfo::class);
    }

    /**
     * @return PageInfo[]
     */
    public function findByParentId(int $parentId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.parent = :parentId')
            ->setParameter('parentId', $parentId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllAsArray(?array $filters = []): array
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.position', 'ASC');

        foreach ($filters as $key => $value) {
            $query->andWhere("p.$key = :$key")
                ->setParameter($key, $value);
        }

        return $query->getQuery()
            ->getArrayResult(); // Associative array
    }

    /**
     * @return PageInfo[]
     */
    public function findRootPages(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.parent IS NULL')
            ->andWhere('p.published = :published')
            ->setParameter('published', true)
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Published root pages displayed in the header, with their children.
     *
     * @return PageInfo[]
     */
    public function findPublishedRootPagesInHeader(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.children', 'c')
            ->addSelect('c')
            ->andWhere('p.parent IS NULL')
            ->andWhere('p.published = :published')
            ->andWhere('p.inHeader = :inHeader')
            ->setParameter('published', true)
            ->setParameter('inHeader', true)
            ->orderBy('p.position', 'ASC')
            ->addOrderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Page[] Returns an array of Page objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Page
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
