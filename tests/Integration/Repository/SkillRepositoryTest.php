<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Skill;
use App\Entity\SkillType;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SkillRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private SkillRepository $skillRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->skillRepository = $this->entityManager->getRepository(Skill::class);

        $this->cleanUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Test that getSkillsWithType returns all skills ordered by position.
     */
    public function testGetSkillsWithTypeOrdersByPosition(): void
    {
        $type = (new SkillType())
            ->setName('Back-end')
            ->setSlug('back-end')
            ->setLogo('back-end.png')
            ->setDeleted(false);
        $this->entityManager->persist($type);

        $skill2 = (new Skill())
            ->setName('PHP')
            ->setSlug('php')
            ->setLogo('php.png')
            ->setStartYear(2020)
            ->setPosition(2)
            ->setSkillType($type)
            ->setLevel(75)
            ->setDisplay(true);
        $skill1 = (new Skill())
            ->setName('Symfony')
            ->setSlug('symfony')
            ->setLogo('symfony.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($type)
            ->setLevel(85)
            ->setDisplay(true);

        $this->entityManager->persist($skill2);
        $this->entityManager->persist($skill1);
        $this->entityManager->flush();

        $results = $this->skillRepository->getSkillsWithType();

        $this->assertCount(2, $results);
        $this->assertSame($skill1, $results[0], 'Position 1 en premier');
        $this->assertSame($skill2, $results[1], 'Position 2 en deuxième');
    }

    /**
     * Test that getSkillsByType filters by ID and excludes deleted types.
     */
    public function testGetSkillsByTypeFiltersCorrectly(): void
    {
        $typeValid = (new SkillType())
            ->setName('Front-end')
            ->setSlug('front-end')
            ->setLogo('front-end.png')
            ->setDeleted(false);
        $typeDeleted = (new SkillType())
            ->setName('Design')
            ->setSlug('design')
            ->setLogo('design.png')
            ->setDeleted(true);
        $typeAutre = (new SkillType())
            ->setName('DevOps')
            ->setSlug('devops')
            ->setLogo('devops.png')
            ->setDeleted(false);

        $this->entityManager->persist($typeValid);
        $this->entityManager->persist($typeDeleted);
        $this->entityManager->persist($typeAutre);

        $skillValid = (new Skill())
            ->setName('Tailwind')
            ->setSlug('tailwind')
            ->setLogo('tailwind.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($typeValid)
            ->setLevel(80)
            ->setDisplay(true);
        $skillInDeletedType = (new Skill())
            ->setName('Photoshop')
            ->setSlug('photoshop')
            ->setLogo('photoshop.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($typeDeleted)
            ->setLevel(70)
            ->setDisplay(true);
        $skillAutreType = (new Skill())
            ->setName('Docker')
            ->setSlug('docker')
            ->setLogo('docker.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($typeAutre)
            ->setLevel(60)
            ->setDisplay(true);

        $this->entityManager->persist($skillValid);
        $this->entityManager->persist($skillInDeletedType);
        $this->entityManager->persist($skillAutreType);
        $this->entityManager->flush();

        // Query only for the valid type ID
        $results = $this->skillRepository->getSkillsByType($typeValid->getId());

        $this->assertCount(1, $results);
        $this->assertSame($skillValid, $results[0]);
    }

    /**
     * Test that getSkillsOrderByType filters by display/deleted
     * and structures the array by type slug.
     */
    public function testGetSkillsOrderByTypeStructuresArrayCorrectly(): void
    {
        $typeBack = (new SkillType())
            ->setName('Back-end')
            ->setSlug('back-end')
            ->setLogo('back-end.png')
            ->setDeleted(false);
        $typeFront = (new SkillType())
            ->setName('Front-end')
            ->setSlug('front-end')
            ->setLogo('front-end.png')
            ->setDeleted(false);
        $this->entityManager->persist($typeBack);
        $this->entityManager->persist($typeFront);

        // Valid skills
        $symfony = (new Skill())
            ->setName('Symfony')
            ->setSlug('symfony')
            ->setLogo('symfony.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($typeBack)
            ->setLevel(85)
            ->setDisplay(true);
        $tailwind = (new Skill())
            ->setName('Tailwind v4')
            ->setSlug('tailwind-v4')
            ->setLogo('tailwind.png')
            ->setStartYear(2020)
            ->setPosition(1)
            ->setSkillType($typeFront)
            ->setLevel(90)
            ->setDisplay(true);

        // Hidden skill (display = false -> should not appear)
        $hiddenSkill = (new Skill())
            ->setName('Vieux truc')
            ->setSlug('vieux-truc')
            ->setLogo('vieux-truc.png')
            ->setStartYear(2020)
            ->setPosition(2)
            ->setSkillType($typeBack)
            ->setLevel(50)
            ->setDisplay(false);

        $this->entityManager->persist($symfony);
        $this->entityManager->persist($tailwind);
        $this->entityManager->persist($hiddenSkill);
        $this->entityManager->flush();

        $results = $this->skillRepository->getSkillsOrderByType();

        // Expect two keys corresponding to the slugs
        $this->assertArrayHasKey('back-end', $results);
        $this->assertArrayHasKey('front-end', $results);

        // Verify content and filters
        $this->assertCount(1, $results['back-end']);
        $this->assertSame($symfony, $results['back-end'][0]);

        $this->assertCount(1, $results['front-end']);
        $this->assertSame($tailwind, $results['front-end'][0]);
    }

    /**
     * Clean up database tables to avoid foreign key constraints.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        // If you have a join table "project_skill", clear it first!
        // $connection->executeStatement('DELETE FROM project_skill');

        $connection->executeStatement('DELETE FROM skill');
        $connection->executeStatement('DELETE FROM skill_type');

        $this->entityManager->clear();
    }
}
