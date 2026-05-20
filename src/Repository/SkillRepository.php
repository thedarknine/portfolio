<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Skill>
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    /**
     * @return Skill[] Returns an array of Skill objects
     */
    public function getSkillsWithType(): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('st')
            ->join('s.skillType', 'st')
            ->orderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Skill[] Returns an array of Skill objects for a given skill type
     */
    public function getSkillsByType(int $skillTypeId): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('st')
            ->join('s.skillType', 'st')
            ->where('st.id = :skillTypeId')
            ->setParameter('skillTypeId', $skillTypeId)
            ->orderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<list<mixed>> Returns an array of Skill objects ordered by their type
     */
    public function getSkillsOrderByType(): array
    {
        $result = $this->createQueryBuilder('skl')
            ->select('skl')
            ->where('skl.display = 1')
            ->innerJoin('skl.skillType', 'type')
            ->addSelect('type')
            ->orderBy('skl.position', 'ASC')
            ->getQuery()
            ->getResult();

        $listing = [];
        foreach ($result as $skill) {
            $listing[$skill->getSkillType()->getLabel()][] = $skill;
        }

        return $listing;
    }
}
