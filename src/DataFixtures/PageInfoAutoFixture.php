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
        $pageInfo0->setCreatedAt(new \DateTime('2026-05-25 09:38:13'));
        $pageInfo0->setUpdatedAt(new \DateTime('2026-07-06 11:29:33'));
        $pageInfo0->setTitle('Expérience');
        $pageInfo0->setSlug('experience');
        $pageInfo0->setPosition(1);
        $pageInfo0->setPublished(true);
        $manager->persist($pageInfo0);
        $this->addReference('pageInfo_1', $pageInfo0);

        $pageInfo1 = new PageInfo();
        $pageInfo1->setTechnicalName('skills');
        $pageInfo1->setTagline('Maîtriser');
        $pageInfo1->setSubtitle('Des outils au service de solutions fiables et utiles');
        $pageInfo1->setQuote('Je cherche à concevoir des produits simples, utiles et durables, avec une approche pragmatique et humaine.');
        $pageInfo1->setInHeader(true);
        $pageInfo1->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo1->setCreatedAt(new \DateTime('2026-05-25 10:06:31'));
        $pageInfo1->setUpdatedAt(new \DateTime('2026-07-06 11:29:33'));
        $pageInfo1->setTitle('Compétences');
        $pageInfo1->setSlug('competences');
        $pageInfo1->setPosition(2);
        $pageInfo1->setPublished(true);
        $manager->persist($pageInfo1);
        $this->addReference('pageInfo_2', $pageInfo1);

        $pageInfo2 = new PageInfo();
        $pageInfo2->setTechnicalName('education');
        $pageInfo2->setTagline('Apprendre');
        $pageInfo2->setSubtitle('Les fondations techniques... et l\'évolution continue');
        $pageInfo2->setQuote('Pour moi, la veille et l\'apprentissage ne s\'arrêtent jamais. C\'est un cycle permanent d\'expérimentation.');
        $pageInfo2->setInHeader(true);
        $pageInfo2->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo2->setCreatedAt(new \DateTime('2026-05-25 11:05:25'));
        $pageInfo2->setUpdatedAt(new \DateTime('2026-07-06 11:29:33'));
        $pageInfo2->setTitle('Formation');
        $pageInfo2->setSlug('formation');
        $pageInfo2->setPosition(3);
        $pageInfo2->setPublished(true);
        $manager->persist($pageInfo2);
        $this->addReference('pageInfo_3', $pageInfo2);

        $pageInfo3 = new PageInfo();
        $pageInfo3->setTechnicalName('projects');
        $pageInfo3->setTagline('Expérimenter');
        $pageInfo3->setSubtitle('Chaque projet est un terrain d’exploration');
        $pageInfo3->setQuote('Coder sans contraintes, tester de nouvelles technos et s\'autoriser à chercher juste pour le plaisir de comprendre.');
        $pageInfo3->setInHeader(true);
        $pageInfo3->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo3->setCreatedAt(new \DateTime('2026-05-25 11:06:39'));
        $pageInfo3->setUpdatedAt(new \DateTime('2026-07-06 11:29:33'));
        $pageInfo3->setTitle('Projets');
        $pageInfo3->setSlug('projets');
        $pageInfo3->setPosition(4);
        $pageInfo3->setPublished(true);
        $manager->persist($pageInfo3);
        $this->addReference('pageInfo_4', $pageInfo3);

        $pageInfo4 = new PageInfo();
        $pageInfo4->setTechnicalName('arcade');
        $pageInfo4->setTagline('Assembler');
        $pageInfo4->setSubtitle('Un défi technique entre nostalgie et pop culture');
        $pageInfo4->setQuote('Quand la passion des vieux jeux rencontre le plaisir de bidouiller le hardware.');
        $pageInfo4->setInHeader(true);
        $pageInfo4->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo4->setCreatedAt(new \DateTime('2026-05-25 12:00:50'));
        $pageInfo4->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo4->setTitle('Arcade');
        $pageInfo4->setSlug('arcade');
        $pageInfo4->setPosition(6);
        $pageInfo4->setPublished(true);
        $manager->persist($pageInfo4);
        $this->addReference('pageInfo_5', $pageInfo4);

        $pageInfo5 = new PageInfo();
        $pageInfo5->setTechnicalName('creations');
        $pageInfo5->setTagline('Façonner');
        $pageInfo5->setSubtitle('Entre matière, patience et intuition');
        $pageInfo5->setQuote('Poser les écrans et laisser parler la matière et l\'imagination.');
        $pageInfo5->setInHeader(false);
        $pageInfo5->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo5->setCreatedAt(new \DateTime('2026-05-25 12:07:06'));
        $pageInfo5->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo5->setTitle('Créations');
        $pageInfo5->setSlug('creations');
        $pageInfo5->setPosition(7);
        $pageInfo5->setPublished(true);
        $manager->persist($pageInfo5);
        $this->addReference('pageInfo_6', $pageInfo5);

        $pageInfo6 = new PageInfo();
        $pageInfo6->setTechnicalName('photos');
        $pageInfo6->setTagline('Observer');
        $pageInfo6->setSubtitle('Regards sur l\'Auvergne, les détails et les lumières');
        $pageInfo6->setQuote('Capturer l\'instant, cadrer une atmosphère et prêter attention aux détails qui échappent au premier coup d\'oeil.');
        $pageInfo6->setInHeader(false);
        $pageInfo6->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo6->setCreatedAt(new \DateTime('2026-05-25 12:07:40'));
        $pageInfo6->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo6->setTitle('Photos');
        $pageInfo6->setSlug('photos');
        $pageInfo6->setPosition(8);
        $pageInfo6->setPublished(true);
        $manager->persist($pageInfo6);
        $this->addReference('pageInfo_7', $pageInfo6);

        $pageInfo7 = new PageInfo();
        $pageInfo7->setTechnicalName('contact');
        $pageInfo7->setTagline('Echanger');
        $pageInfo7->setSubtitle('Un projet, une question ou juste envie de discuter ?');
        $pageInfo7->setQuote('Que ce soit pour parler produit, partager une astuce tech ou échanger autour d\'une passion commune, ma boîte mail est grande ouverte.');
        $pageInfo7->setInHeader(true);
        $pageInfo7->setCategory(\App\Enum\PageCategory::INTEREST);
        $pageInfo7->setCreatedAt(new \DateTime('2026-06-17 18:48:45'));
        $pageInfo7->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo7->setTitle('Contact');
        $pageInfo7->setSlug('contact');
        $pageInfo7->setPosition(9);
        $pageInfo7->setPublished(true);
        $manager->persist($pageInfo7);
        $this->addReference('pageInfo_176', $pageInfo7);

        $pageInfo8 = new PageInfo();
        $pageInfo8->setTechnicalName('legal');
        $pageInfo8->setTagline('None');
        $pageInfo8->setSubtitle('None');
        $pageInfo8->setQuote('None');
        $pageInfo8->setInHeader(false);
        $pageInfo8->setCategory(\App\Enum\PageCategory::ABOUT);
        $pageInfo8->setCreatedAt(new \DateTime('2026-06-18 21:57:55'));
        $pageInfo8->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo8->setTitle('Mentions légales');
        $pageInfo8->setSlug('mentions-legales');
        $pageInfo8->setPosition(10);
        $pageInfo8->setPublished(true);
        $manager->persist($pageInfo8);
        $this->addReference('pageInfo_177', $pageInfo8);

        $pageInfo9 = new PageInfo();
        $pageInfo9->setTechnicalName('structuration-pole-produit');
        $pageInfo9->setTagline('Construire');
        $pageInfo9->setSubtitle('Structuration du pôle produit');
        $pageInfo9->setQuote('De l’inexistant à l’opérationnel : comment j’ai donné une structure claire à un pôle produit en création');
        $pageInfo9->setInHeader(false);
        $pageInfo9->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo9->setCreatedAt(new \DateTime('2026-07-06 10:11:01'));
        $pageInfo9->setUpdatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo9->setTitle('Structuration Pôle Produit');
        $pageInfo9->setSlug('structuration-pole-produit');
        $pageInfo9->setPosition(11);
        $pageInfo9->setPublished(true);
        try {
            $pageInfo9->setParent($this->getReference('pageInfo_1', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($pageInfo9);
        $this->addReference('pageInfo_179', $pageInfo9);

        $pageInfo10 = new PageInfo();
        $pageInfo10->setTechnicalName('produit-expose');
        $pageInfo10->setTagline('Valoriser');
        $pageInfo10->setSubtitle('Brique B2B2C - Exposé™');
        $pageInfo10->setQuote('Exposé™ – Comment j’ai lancé une nouvelle brique BtoBtoC pour monétiser les actifs numériques');
        $pageInfo10->setInHeader(false);
        $pageInfo10->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo10->setCreatedAt(new \DateTime('2026-07-06 11:11:31'));
        $pageInfo10->setUpdatedAt(new \DateTime('2026-07-16 16:09:41'));
        $pageInfo10->setTitle('Produit Exposé');
        $pageInfo10->setSlug('produit-expose');
        $pageInfo10->setPosition(13);
        $pageInfo10->setPublished(true);
        try {
            $pageInfo10->setParent($this->getReference('pageInfo_1', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($pageInfo10);
        $this->addReference('pageInfo_180', $pageInfo10);

        $pageInfo11 = new PageInfo();
        $pageInfo11->setTechnicalName('produit-timesheet');
        $pageInfo11->setTagline('Développer');
        $pageInfo11->setSubtitle('Nouvelle brique B2B - Timesheet');
        $pageInfo11->setQuote('Timesheet – Comment j’ai digitalisé le relevé d’heures pour 600 agences d’intérim');
        $pageInfo11->setInHeader(false);
        $pageInfo11->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo11->setCreatedAt(new \DateTime('2026-07-11 13:17:36'));
        $pageInfo11->setUpdatedAt(new \DateTime('2026-07-16 16:08:54'));
        $pageInfo11->setTitle('Produit Timesheet');
        $pageInfo11->setSlug('produit-timesheet');
        $pageInfo11->setPosition(12);
        $pageInfo11->setPublished(true);
        try {
            $pageInfo11->setParent($this->getReference('pageInfo_1', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($pageInfo11);
        $this->addReference('pageInfo_181', $pageInfo11);

        $pageInfo12 = new PageInfo();
        $pageInfo12->setTechnicalName('refonte-app-mobile');
        $pageInfo12->setTagline('Améliorer');
        $pageInfo12->setSubtitle('Refonte d\'une application mobile B2C');
        $pageInfo12->setQuote('Comment j’ai sauvé l’app mobile de Coffreo (et boosté son engagement)');
        $pageInfo12->setInHeader(false);
        $pageInfo12->setCategory(\App\Enum\PageCategory::CAREER);
        $pageInfo12->setCreatedAt(new \DateTime('2026-07-13 21:34:08'));
        $pageInfo12->setUpdatedAt(new \DateTime('2026-07-16 16:09:58'));
        $pageInfo12->setTitle('Refonte app mobile');
        $pageInfo12->setSlug('refonte-app-mobile');
        $pageInfo12->setPosition(5);
        $pageInfo12->setPublished(true);
        try {
            $pageInfo12->setParent($this->getReference('pageInfo_1', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($pageInfo12);
        $this->addReference('pageInfo_182', $pageInfo12);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
