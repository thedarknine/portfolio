<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\ExperienceItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceItemAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $experienceItem0 = new ExperienceItem();
        $experienceItem0->setTitle('Pilotage produit transverse');
        $experienceItem0->setDetails('Product Owner d’une plateforme SaaS collaborative, interface entre produit, développement et infrastructure, avec pilotage d’une équipe transverse.');
        $experienceItem0->setPosition(1);
        $experienceItem0->setPicto('🔗');
        try {
            $experienceItem0->setExperience($this->getReference('experience_10', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem0);
        $this->addReference('experienceItem_1', $experienceItem0);

        $experienceItem1 = new ExperienceItem();
        $experienceItem1->setTitle('Structuration et priorisation du backlog');
        $experienceItem1->setDetails('Mise en place d’un backlog produit structuré, permettant de clarifier les priorités et d’aligner les équipes sur les enjeux métier et techniques.');
        $experienceItem1->setPosition(2);
        $experienceItem1->setPicto('🧩');
        try {
            $experienceItem1->setExperience($this->getReference('experience_10', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem1);
        $this->addReference('experienceItem_2', $experienceItem1);

        $experienceItem2 = new ExperienceItem();
        $experienceItem2->setTitle('Optimisation des processus et automatisation');
        $experienceItem2->setDetails('Automatisation de tâches via n8n et amélioration des workflows, réduisant les opérations manuelles et augmentant l’efficacité globale.');
        $experienceItem2->setPosition(3);
        $experienceItem2->setPicto('⚙️');
        try {
            $experienceItem2->setExperience($this->getReference('experience_10', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem2);
        $this->addReference('experienceItem_3', $experienceItem2);

        $experienceItem3 = new ExperienceItem();
        $experienceItem3->setTitle('Amélioration continue de la qualité produit');
        $experienceItem3->setDetails('Contribution active à la qualité technique des livrables et fluidification de la collaboration entre développeurs et sysadmins.');
        $experienceItem3->setPosition(4);
        $experienceItem3->setPicto('🚀');
        try {
            $experienceItem3->setExperience($this->getReference('experience_10', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem3);
        $this->addReference('experienceItem_4', $experienceItem3);

        $experienceItem4 = new ExperienceItem();
        $experienceItem4->setTitle('Structuration du pôle produit');
        $experienceItem4->setDetails('Mise en place d’un cadre structurant pour un pôle produit en création, incluant un référentiel centralisé des fonctionnalités et une organisation claire des squads.');
        $experienceItem4->setPosition(1);
        $experienceItem4->setPicto('🧠');
        try {
            $experienceItem4->setExperience($this->getReference('experience_9', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem4);
        $this->addReference('experienceItem_5', $experienceItem4);

        $experienceItem5 = new ExperienceItem();
        $experienceItem5->setTitle('Mise en place de la discovery produit');
        $experienceItem5->setDetails('Déploiement d’un outil de discovery et de priorisation (Jira Product Discovery), avec plus de 300 idées collectées auprès des clients, prospects et équipes internes.');
        $experienceItem5->setPosition(2);
        $experienceItem5->setPicto('🎯');
        try {
            $experienceItem5->setExperience($this->getReference('experience_9', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem5);
        $this->addReference('experienceItem_6', $experienceItem5);

        $experienceItem6 = new ExperienceItem();
        $experienceItem6->setTitle('Transformation des idées en produit');
        $experienceItem6->setDetails('Plus de 100 idées priorisées et mises en production en 8 mois, contribuant directement à l’évolution et à l’enrichissement de l’offre produit.');
        $experienceItem6->setPosition(3);
        $experienceItem6->setPicto('📈');
        try {
            $experienceItem6->setExperience($this->getReference('experience_9', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem6);
        $this->addReference('experienceItem_7', $experienceItem6);

        $experienceItem7 = new ExperienceItem();
        $experienceItem7->setTitle('Contribution à un produit innovant et lancement MVP');
        $experienceItem7->setDetails('Participation à la conception d’un produit B2B basé sur l’asset management et l’IA, avec définition du MVP et mise en production pour un acteur national.');
        $experienceItem7->setPosition(4);
        $experienceItem7->setPicto('🚀');
        try {
            $experienceItem7->setExperience($this->getReference('experience_9', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem7);
        $this->addReference('experienceItem_8', $experienceItem7);

        $experienceItem8 = new ExperienceItem();
        $experienceItem8->setTitle('Lancement d’un produit SaaS stratégique');
        $experienceItem8->setDetails('Conception et lancement d’un produit de relevé d’heures digitalisé, remplaçant des processus manuels et permettant d’accélérer la facturation et la paie.');
        $experienceItem8->setPosition(1);
        $experienceItem8->setPicto('🚀');
        try {
            $experienceItem8->setExperience($this->getReference('experience_8', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem8);
        $this->addReference('experienceItem_9', $experienceItem8);

        $experienceItem9 = new ExperienceItem();
        $experienceItem9->setTitle('Leadership technique et produit');
        $experienceItem9->setDetails('Pilotage d’une équipe de 4 développeurs en tant que Squad Leader, avec responsabilité sur l’architecture, le delivery et l’évolution produit.');
        $experienceItem9->setPosition(2);
        $experienceItem9->setPicto('👥');
        try {
            $experienceItem9->setExperience($this->getReference('experience_8', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem9);
        $this->addReference('experienceItem_10', $experienceItem9);

        $experienceItem10 = new ExperienceItem();
        $experienceItem10->setTitle('Delivery produit de bout en bout');
        $experienceItem10->setDetails('Cadrage, développement, pilotage des versions, phase pilote avec clients et mise en production, avec accompagnement des utilisateurs et amélioration continue.');
        $experienceItem10->setPosition(3);
        $experienceItem10->setPicto('📈');
        try {
            $experienceItem10->setExperience($this->getReference('experience_8', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem10);
        $this->addReference('experienceItem_11', $experienceItem10);

        $experienceItem11 = new ExperienceItem();
        $experienceItem11->setTitle('Refonte d’une application B2C');
        $experienceItem11->setDetails('Refonte complète d’une application mobile (500k visites/mois), avec amélioration de l’engagement utilisateur, réduction des tickets support et hausse des notes sur les stores.');
        $experienceItem11->setPosition(4);
        $experienceItem11->setPicto('📱');
        try {
            $experienceItem11->setExperience($this->getReference('experience_8', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem11);
        $this->addReference('experienceItem_12', $experienceItem11);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            ExperienceAutoFixture::class,
        ];
    }
}
