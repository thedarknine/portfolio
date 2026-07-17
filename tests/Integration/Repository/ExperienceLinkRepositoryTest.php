<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Company;
use App\Entity\Experience;
use App\Entity\ExperienceLink;
use App\Entity\PageInfo;
use App\Enum\LinkType;
use App\Enum\PageCategory;
use App\Repository\ExperienceLinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExperienceLinkRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ExperienceLinkRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->repository    = $this->entityManager->getRepository(ExperienceLink::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->entityManager, $this->repository);
    }

    public function testRepositoryIsInstantiable(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get('doctrine.orm.entity_manager')->getRepository(ExperienceLink::class);

        $this->assertInstanceOf(ExperienceLinkRepository::class, $repository);
    }

    public function testFindOneByPageReturnsMatchingLink(): void
    {
        $page       = $this->createPersistedPage('page-with-link');
        $experience = $this->createPersistedExperience();

        $link = new ExperienceLink();
        $link->setTitle('Voir le détail');
        $link->setType(LinkType::DETAIL);
        $link->setPage($page);
        $link->setSlug('voir-le-detail');
        $link->setExperience($experience);

        $this->entityManager->persist($link);
        $this->entityManager->flush();

        $result = $this->repository->findOneByPage($page);

        $this->assertNotNull($result);
        $this->assertSame($link->getId(), $result->getId());
    }

    public function testFindOneByPageReturnsNullWhenNoLinkExists(): void
    {
        $page = $this->createPersistedPage('page-without-link');

        $result = $this->repository->findOneByPage($page);

        $this->assertNull($result);
    }

    private function createPersistedPage(string $technicalName): PageInfo
    {
        $page = new PageInfo();
        $page->setTechnicalName($technicalName);
        $page->setTitle('Test page');
        $page->setSlug($technicalName);
        $page->setTagline('Tagline');
        $page->setSubtitle('Subtitle');
        $page->setQuote('Quote');
        $page->setInHeader(false);
        $page->setCategory(PageCategory::CAREER);

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        return $page;
    }

    private function createPersistedExperience(): Experience
    {
        $company = new Company();
        $company->setName('Mon Entreprise Retro');
        $company->setSlug('mon-entreprise-retro');
        $company->setCity('Orléans');
        $company->setDepartment(45);
        $company->setLogo('logo.png');
        $company->setUrl('https://carolinenoyer.fr');

        $this->entityManager->persist($company);

        $experience = new Experience();
        $experience->setTitle('Developer');
        $experience->setSlug('developer');
        $experience->setDescription('Valid description');
        $experience->setCompany($company);
        $experience->setStartDate(new \DateTime('2026-05-01'));
        $experience->setEndDate(new \DateTime('2026-01-01'));

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        return $experience;
    }
}
