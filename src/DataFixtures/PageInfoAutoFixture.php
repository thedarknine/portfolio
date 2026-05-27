<?php

namespace App\DataFixtures;

use App\Entity\PageInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PageInfoAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pageInfo0 = new PageInfo();
        $pageInfo0->setTechnicalName('experience');
        $pageInfo0->setTagline('Construire');
        $pageInfo0->setSubtitle('Du code au produit : mon parcours en mouvement');
        $pageInfo0->setQuote('J’aime transformer des idées en réalisations concrètes, apprendre en faisant et faire évoluer ce que je construis.');
        $pageInfo0->setInHeader(true);
        $pageInfo0->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo0->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo0->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo0->setTitle('Expérience');
        $pageInfo0->setSlug('experience');
        $pageInfo0->setPosition(1);
        $manager->persist($pageInfo0);
        $this->addReference('pageInfo_1', $pageInfo0);

        $pageInfo1 = new PageInfo();
        $pageInfo1->setTechnicalName('skills');
        $pageInfo1->setTagline('Maîtriser');
        $pageInfo1->setSubtitle('Des outils au service de solutions fiables et utiles');
        $pageInfo1->setQuote('Je cherche à concevoir des produits simples, utiles et durables, avec une approche pragmatique et humaine.');
        $pageInfo1->setInHeader(true);
        $pageInfo1->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo1->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo1->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo1->setTitle('Compétences');
        $pageInfo1->setSlug('competences');
        $pageInfo1->setPosition(2);
        $manager->persist($pageInfo1);
        $this->addReference('pageInfo_2', $pageInfo1);

        $pageInfo2 = new PageInfo();
        $pageInfo2->setTechnicalName('education');
        $pageInfo2->setTagline('Apprendre');
        $pageInfo2->setSubtitle('Les fondations techniques... et l\'évolution continue');
        $pageInfo2->setQuote('Pour moi, la veille et l\'apprentissage ne s\'arrêtent jamais. C\'est un cycle permanent d\'expérimentation.');
        $pageInfo2->setInHeader(true);
        $pageInfo2->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo2->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo2->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo2->setTitle('Formation');
        $pageInfo2->setSlug('formation');
        $pageInfo2->setPosition(3);
        $manager->persist($pageInfo2);
        $this->addReference('pageInfo_3', $pageInfo2);

        $pageInfo3 = new PageInfo();
        $pageInfo3->setTechnicalName('projects');
        $pageInfo3->setTagline('Expérimenter');
        $pageInfo3->setSubtitle('Chaque projet est un terrain d’exploration');
        $pageInfo3->setQuote('Coder sans contraintes, tester de nouvelles technos et s\'autoriser à chercher juste pour le plaisir de comprendre.');
        $pageInfo3->setInHeader(true);
        $pageInfo3->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo3->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo3->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo3->setTitle('Projets');
        $pageInfo3->setSlug('projets');
        $pageInfo3->setPosition(4);
        $manager->persist($pageInfo3);
        $this->addReference('pageInfo_4', $pageInfo3);

        $pageInfo4 = new PageInfo();
        $pageInfo4->setTechnicalName('arcade');
        $pageInfo4->setTagline('Assembler');
        $pageInfo4->setSubtitle('Un défi technique entre nostalgie et pop culture');
        $pageInfo4->setQuote('Quand la passion des vieux jeux rencontre le plaisir de bidouiller le hardware.');
        $pageInfo4->setInHeader(true);
        $pageInfo4->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo4->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo4->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo4->setTitle('Arcade');
        $pageInfo4->setSlug('arcade');
        $pageInfo4->setPosition(5);
        $manager->persist($pageInfo4);
        $this->addReference('pageInfo_5', $pageInfo4);

        $pageInfo5 = new PageInfo();
        $pageInfo5->setTechnicalName('creations');
        $pageInfo5->setTagline('Façonner');
        $pageInfo5->setSubtitle('Entre matière, patience et intuition');
        $pageInfo5->setQuote('Poser les écrans et laisser parler la matière et l\'imagination.');
        $pageInfo5->setInHeader(false);
        $pageInfo5->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo5->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo5->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo5->setTitle('Créations');
        $pageInfo5->setSlug('creations');
        $pageInfo5->setPosition(6);
        $manager->persist($pageInfo5);
        $this->addReference('pageInfo_6', $pageInfo5);

        $pageInfo6 = new PageInfo();
        $pageInfo6->setTechnicalName('photos');
        $pageInfo6->setTagline('Observer');
        $pageInfo6->setSubtitle('Regards sur l\'Auvergne, les détails et les lumières');
        $pageInfo6->setQuote('Capturer l\'instant, cadrer une atmosphère et prêter attention aux détails qui échappent au premier coup d\'oeil.');
        $pageInfo6->setInHeader(false);
        $pageInfo6->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo6->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo6->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $pageInfo6->setTitle('Photos');
        $pageInfo6->setSlug('photos');
        $pageInfo6->setPosition(7);
        $manager->persist($pageInfo6);
        $this->addReference('pageInfo_7', $pageInfo6);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
