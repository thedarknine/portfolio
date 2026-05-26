<?php

namespace App\DataFixtures;

use App\Entity\ExperienceItem;
use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceItemAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $experienceItem0 = new ExperienceItem();
        $experienceItem0->setDetails('Product Owner d’une plateforme SaaS collaborative, interface entre produit, développement et infrastructure, avec pilotage d’une équipe transverse.');
        $experienceItem0->setPosition(1);
        $experienceItem0->setPicto('🔗');
        $experienceItem0->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem0->setUpdatedAt(null);
        $experienceItem0->setTitle('Pilotage produit transverse');
        try {
            $experienceItem0->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem0);
        $this->addReference('experienceItem_13', $experienceItem0);

        $experienceItem1 = new ExperienceItem();
        $experienceItem1->setDetails('Mise en place d’un backlog produit structuré, permettant de clarifier les priorités et d’aligner les équipes sur les enjeux métier et techniques.');
        $experienceItem1->setPosition(2);
        $experienceItem1->setPicto('🧩');
        $experienceItem1->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem1->setUpdatedAt(null);
        $experienceItem1->setTitle('Structuration et priorisation du backlog');
        try {
            $experienceItem1->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem1);
        $this->addReference('experienceItem_14', $experienceItem1);

        $experienceItem2 = new ExperienceItem();
        $experienceItem2->setDetails('Automatisation de tâches via n8n et amélioration des workflows, réduisant les opérations manuelles et augmentant l’efficacité globale.');
        $experienceItem2->setPosition(3);
        $experienceItem2->setPicto('⚙️');
        $experienceItem2->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem2->setUpdatedAt(null);
        $experienceItem2->setTitle('Optimisation des processus et automatisation');
        try {
            $experienceItem2->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem2);
        $this->addReference('experienceItem_15', $experienceItem2);

        $experienceItem3 = new ExperienceItem();
        $experienceItem3->setDetails('Contribution active à la qualité technique des livrables et fluidification de la collaboration entre développeurs et sysadmins.');
        $experienceItem3->setPosition(4);
        $experienceItem3->setPicto('🚀');
        $experienceItem3->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem3->setUpdatedAt(null);
        $experienceItem3->setTitle('Amélioration continue de la qualité produit');
        try {
            $experienceItem3->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem3);
        $this->addReference('experienceItem_16', $experienceItem3);

        $experienceItem4 = new ExperienceItem();
        $experienceItem4->setDetails('Mise en place d’un cadre structurant pour un pôle produit en création, incluant un référentiel centralisé des fonctionnalités et une organisation claire des squads.');
        $experienceItem4->setPosition(1);
        $experienceItem4->setPicto('🧠');
        $experienceItem4->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem4->setUpdatedAt(null);
        $experienceItem4->setTitle('Structuration du pôle produit');
        try {
            $experienceItem4->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem4);
        $this->addReference('experienceItem_17', $experienceItem4);

        $experienceItem5 = new ExperienceItem();
        $experienceItem5->setDetails('Déploiement d’un outil de discovery et de priorisation (Jira Product Discovery), avec plus de 300 idées collectées auprès des clients, prospects et équipes internes.');
        $experienceItem5->setPosition(2);
        $experienceItem5->setPicto('🎯');
        $experienceItem5->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem5->setUpdatedAt(null);
        $experienceItem5->setTitle('Mise en place de la discovery produit');
        try {
            $experienceItem5->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem5);
        $this->addReference('experienceItem_18', $experienceItem5);

        $experienceItem6 = new ExperienceItem();
        $experienceItem6->setDetails('Plus de 100 idées priorisées et mises en production en 8 mois, contribuant directement à l’évolution et à l’enrichissement de l’offre produit.');
        $experienceItem6->setPosition(3);
        $experienceItem6->setPicto('📈');
        $experienceItem6->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem6->setUpdatedAt(null);
        $experienceItem6->setTitle('Transformation des idées en produit');
        try {
            $experienceItem6->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem6);
        $this->addReference('experienceItem_19', $experienceItem6);

        $experienceItem7 = new ExperienceItem();
        $experienceItem7->setDetails('Participation à la conception d’un produit B2B basé sur l’asset management et l’IA, avec définition du MVP et mise en production pour un acteur national.');
        $experienceItem7->setPosition(4);
        $experienceItem7->setPicto('🚀');
        $experienceItem7->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem7->setUpdatedAt(null);
        $experienceItem7->setTitle('Contribution à un produit innovant et lancement MVP');
        try {
            $experienceItem7->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem7);
        $this->addReference('experienceItem_20', $experienceItem7);

        $experienceItem8 = new ExperienceItem();
        $experienceItem8->setDetails('Conception et lancement d’un produit de relevé d’heures digitalisé, remplaçant des processus manuels et permettant d’accélérer la facturation et la paie.');
        $experienceItem8->setPosition(1);
        $experienceItem8->setPicto('🚀');
        $experienceItem8->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem8->setUpdatedAt(null);
        $experienceItem8->setTitle('Lancement d’un produit SaaS stratégique');
        try {
            $experienceItem8->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem8);
        $this->addReference('experienceItem_21', $experienceItem8);

        $experienceItem9 = new ExperienceItem();
        $experienceItem9->setDetails('Pilotage d’une équipe de 4 développeurs en tant que Squad Leader, avec responsabilité sur l’architecture, le delivery et l’évolution produit.');
        $experienceItem9->setPosition(2);
        $experienceItem9->setPicto('👥');
        $experienceItem9->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem9->setUpdatedAt(null);
        $experienceItem9->setTitle('Leadership technique et produit');
        try {
            $experienceItem9->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem9);
        $this->addReference('experienceItem_22', $experienceItem9);

        $experienceItem10 = new ExperienceItem();
        $experienceItem10->setDetails('Cadrage, développement, pilotage des versions, phase pilote avec clients et mise en production, avec accompagnement des utilisateurs et amélioration continue.');
        $experienceItem10->setPosition(3);
        $experienceItem10->setPicto('📈');
        $experienceItem10->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem10->setUpdatedAt(null);
        $experienceItem10->setTitle('Delivery produit de bout en bout');
        try {
            $experienceItem10->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem10);
        $this->addReference('experienceItem_23', $experienceItem10);

        $experienceItem11 = new ExperienceItem();
        $experienceItem11->setDetails('Refonte complète d’une application mobile (500k visites/mois), avec amélioration de l’engagement utilisateur, réduction des tickets support et hausse des notes sur les stores.');
        $experienceItem11->setPosition(4);
        $experienceItem11->setPicto('📱');
        $experienceItem11->setCreatedAt(new \DateTime('2026-05-21 19:38:39'));
        $experienceItem11->setUpdatedAt(null);
        $experienceItem11->setTitle('Refonte d’une application B2C');
        try {
            $experienceItem11->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem11);
        $this->addReference('experienceItem_24', $experienceItem11);

        $experienceItem12 = new ExperienceItem();
        $experienceItem12->setDetails('Squad leader sur un nouveau produit de l’offre Coffreo Pro, avec coordination des développements.');
        $experienceItem12->setPosition(1);
        $experienceItem12->setPicto('🧭');
        $experienceItem12->setCreatedAt(new \DateTime('2026-05-23 20:03:42'));
        $experienceItem12->setUpdatedAt(null);
        $experienceItem12->setTitle('Pilotage technique d’un produit en construction');
        try {
            $experienceItem12->setExperience($this->getReference('experience_17', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem12);
        $this->addReference('experienceItem_25', $experienceItem12);

        $experienceItem13 = new ExperienceItem();
        $experienceItem13->setDetails('Analyse des besoins métiers et rédaction des spécifications techniques afin de structurer les évolutions du produit.');
        $experienceItem13->setPosition(2);
        $experienceItem13->setPicto('📝');
        $experienceItem13->setCreatedAt(new \DateTime('2026-05-23 20:03:42'));
        $experienceItem13->setUpdatedAt(null);
        $experienceItem13->setTitle('Conception et cadrage des développements');
        try {
            $experienceItem13->setExperience($this->getReference('experience_17', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem13);
        $this->addReference('experienceItem_26', $experienceItem13);

        $experienceItem14 = new ExperienceItem();
        $experienceItem14->setDetails('Développement de services et APIs en PHP avec Symfony dans une architecture microservices, incluant la création d’un service basé sur API Platform.');
        $experienceItem14->setPosition(3);
        $experienceItem14->setPicto('⚙️');
        $experienceItem14->setCreatedAt(new \DateTime('2026-05-23 20:03:42'));
        $experienceItem14->setUpdatedAt(null);
        $experienceItem14->setTitle('Développement backend orienté microservices');
        try {
            $experienceItem14->setExperience($this->getReference('experience_17', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem14);
        $this->addReference('experienceItem_27', $experienceItem14);

        $experienceItem15 = new ExperienceItem();
        $experienceItem15->setDetails('Collaboration avec la direction, suivi des développements et accompagnement des clients sur les problématiques techniques du produit.');
        $experienceItem15->setPosition(4);
        $experienceItem15->setPicto('🤝');
        $experienceItem15->setCreatedAt(new \DateTime('2026-05-23 20:03:42'));
        $experienceItem15->setUpdatedAt(null);
        $experienceItem15->setTitle('Suivi produit et support client');
        try {
            $experienceItem15->setExperience($this->getReference('experience_17', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem15);
        $this->addReference('experienceItem_28', $experienceItem15);

        $experienceItem16 = new ExperienceItem();
        $experienceItem16->setDetails('Réalisation de l’identité graphique et des interfaces du projet PREV@PASS, pensées pour faciliter l’usage par les professionnels de santé.');
        $experienceItem16->setPosition(1);
        $experienceItem16->setPicto('🎨');
        $experienceItem16->setCreatedAt(new \DateTime('2026-05-23 20:08:21'));
        $experienceItem16->setUpdatedAt(null);
        $experienceItem16->setTitle('Conception de l’interface utilisateur');
        try {
            $experienceItem16->setExperience($this->getReference('experience_16', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem16);
        $this->addReference('experienceItem_29', $experienceItem16);

        $experienceItem17 = new ExperienceItem();
        $experienceItem17->setDetails('Développement frontend et backend d’une application métier basée sur une architecture MVC avec le micro-framework Silex.');
        $experienceItem17->setPosition(2);
        $experienceItem17->setPicto('💻');
        $experienceItem17->setCreatedAt(new \DateTime('2026-05-23 20:08:21'));
        $experienceItem17->setUpdatedAt(null);
        $experienceItem17->setTitle('Développement fullstack de la plateforme');
        try {
            $experienceItem17->setExperience($this->getReference('experience_16', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem17);
        $this->addReference('experienceItem_30', $experienceItem17);

        $experienceItem18 = new ExperienceItem();
        $experienceItem18->setDetails('Développement de webservices permettant la communication et la synchronisation des données entre différents acteurs du parcours de soins.');
        $experienceItem18->setPosition(3);
        $experienceItem18->setPicto('🔌');
        $experienceItem18->setCreatedAt(new \DateTime('2026-05-23 20:08:21'));
        $experienceItem18->setUpdatedAt(null);
        $experienceItem18->setTitle('Mise en place de services interconnectés');
        try {
            $experienceItem18->setExperience($this->getReference('experience_16', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem18);
        $this->addReference('experienceItem_31', $experienceItem18);

        $experienceItem19 = new ExperienceItem();
        $experienceItem19->setDetails('Création de scripts techniques pour automatiser certaines opérations et améliorer la fiabilité des traitements applicatifs.');
        $experienceItem19->setPosition(4);
        $experienceItem19->setPicto('⚙️');
        $experienceItem19->setCreatedAt(new \DateTime('2026-05-23 20:08:21'));
        $experienceItem19->setUpdatedAt(null);
        $experienceItem19->setTitle('Automatisation et optimisation des traitements');
        try {
            $experienceItem19->setExperience($this->getReference('experience_16', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem19);
        $this->addReference('experienceItem_32', $experienceItem19);

        $experienceItem20 = new ExperienceItem();
        $experienceItem20->setDetails('Conception et développement d’une plateforme interne dédiée au suivi et à la gestion de projets pour les équipes de production.');
        $experienceItem20->setPosition(1);
        $experienceItem20->setPicto('🛠️');
        $experienceItem20->setCreatedAt(new \DateTime('2026-05-23 20:11:53'));
        $experienceItem20->setUpdatedAt(null);
        $experienceItem20->setTitle('Développement d’outil interne de gestion');
        try {
            $experienceItem20->setExperience($this->getReference('experience_15', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem20);
        $this->addReference('experienceItem_33', $experienceItem20);

        $experienceItem21 = new ExperienceItem();
        $experienceItem21->setDetails('Mise en place de solutions facilitant le travail en équipe et la circulation des informations au sein de l’entreprise.');
        $experienceItem21->setPosition(2);
        $experienceItem21->setPicto('🤝');
        $experienceItem21->setCreatedAt(new \DateTime('2026-05-23 20:11:53'));
        $experienceItem21->setUpdatedAt(null);
        $experienceItem21->setTitle('Amélioration des outils collaboratifs');
        try {
            $experienceItem21->setExperience($this->getReference('experience_15', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem21);
        $this->addReference('experienceItem_34', $experienceItem21);

        $experienceItem22 = new ExperienceItem();
        $experienceItem22->setDetails('Maintenance des sites de l’entreprise et du support produit, avec participation à la sécurisation du logiciel Substance.');
        $experienceItem22->setPosition(3);
        $experienceItem22->setPicto('🌐');
        $experienceItem22->setCreatedAt(new \DateTime('2026-05-23 20:11:53'));
        $experienceItem22->setUpdatedAt(null);
        $experienceItem22->setTitle('Maintenance et sécurisation des services web');
        try {
            $experienceItem22->setExperience($this->getReference('experience_15', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem22);
        $this->addReference('experienceItem_35', $experienceItem22);

        $experienceItem23 = new ExperienceItem();
        $experienceItem23->setDetails('Gestion d’environnements Windows et Linux ainsi que de l’infrastructure réseau, incluant firewall MonoWall et espaces de stockage FreeNAS.');
        $experienceItem23->setPosition(4);
        $experienceItem23->setPicto('🖥️');
        $experienceItem23->setCreatedAt(new \DateTime('2026-05-23 20:11:53'));
        $experienceItem23->setUpdatedAt(null);
        $experienceItem23->setTitle('Administration systèmes et réseau');
        try {
            $experienceItem23->setExperience($this->getReference('experience_15', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem23);
        $this->addReference('experienceItem_36', $experienceItem23);

        $experienceItem24 = new ExperienceItem();
        $experienceItem24->setDetails('Participation à la rédaction des cahiers des charges et à la planification des projets afin d’assurer un suivi structuré des développements.');
        $experienceItem24->setPosition(1);
        $experienceItem24->setPicto('📋');
        $experienceItem24->setCreatedAt(new \DateTime('2026-05-23 20:15:08'));
        $experienceItem24->setUpdatedAt(null);
        $experienceItem24->setTitle('Cadrage et organisation des projets web');
        try {
            $experienceItem24->setExperience($this->getReference('experience_14', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem24);
        $this->addReference('experienceItem_37', $experienceItem24);

        $experienceItem25 = new ExperienceItem();
        $experienceItem25->setDetails('Conception et développement de solutions web sur mesure répondant aux besoins des clients et des projets internes.');
        $experienceItem25->setPosition(2);
        $experienceItem25->setPicto('💻');
        $experienceItem25->setCreatedAt(new \DateTime('2026-05-23 20:15:08'));
        $experienceItem25->setUpdatedAt(null);
        $experienceItem25->setTitle('Développement de sites et applications web');
        try {
            $experienceItem25->setExperience($this->getReference('experience_14', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem25);
        $this->addReference('experienceItem_38', $experienceItem25);

        $experienceItem26 = new ExperienceItem();
        $experienceItem26->setDetails('Réalisation d’interfaces conformes aux normes W3C avec une attention portée à la qualité et à la compatibilité des intégrations.');
        $experienceItem26->setPosition(3);
        $experienceItem26->setPicto('🎨');
        $experienceItem26->setCreatedAt(new \DateTime('2026-05-23 20:15:08'));
        $experienceItem26->setUpdatedAt(null);
        $experienceItem26->setTitle('Intégration web respectueuse des standards');
        try {
            $experienceItem26->setExperience($this->getReference('experience_14', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem26);
        $this->addReference('experienceItem_39', $experienceItem26);

        $experienceItem27 = new ExperienceItem();
        $experienceItem27->setDetails('Développement des solutions NevoTec et NevoPrint, orientées automatisation et usages métier spécifiques.');
        $experienceItem27->setPosition(4);
        $experienceItem27->setPicto('🚀');
        $experienceItem27->setCreatedAt(new \DateTime('2026-05-23 20:15:08'));
        $experienceItem27->setUpdatedAt(null);
        $experienceItem27->setTitle('Contribution à des outils métiers innovants');
        try {
            $experienceItem27->setExperience($this->getReference('experience_14', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem27);
        $this->addReference('experienceItem_40', $experienceItem27);

        $experienceItem28 = new ExperienceItem();
        $experienceItem28->setDetails('Développement d’un assistant permettant de faciliter et standardiser la rédaction de cahiers des charges et documents projet.');
        $experienceItem28->setPosition(1);
        $experienceItem28->setPicto('🧠');
        $experienceItem28->setCreatedAt(new \DateTime('2026-05-23 20:18:58'));
        $experienceItem28->setUpdatedAt(null);
        $experienceItem28->setTitle('Conception d’un outil de génération documentaire');
        try {
            $experienceItem28->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem28);
        $this->addReference('experienceItem_41', $experienceItem28);

        $experienceItem29 = new ExperienceItem();
        $experienceItem29->setDetails('Mise en place d’un système de génération automatique de documents PDF à partir de sources LaTeX et de questionnaires dynamiques.');
        $experienceItem29->setPosition(2);
        $experienceItem29->setPicto('📄');
        $experienceItem29->setCreatedAt(new \DateTime('2026-05-23 20:18:58'));
        $experienceItem29->setUpdatedAt(null);
        $experienceItem29->setTitle('Automatisation de la production PDF');
        try {
            $experienceItem29->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem29);
        $this->addReference('experienceItem_42', $experienceItem29);

        $experienceItem30 = new ExperienceItem();
        $experienceItem30->setDetails('Contribution à la structuration des workflows de production afin de réduire les tâches répétitives et accélérer la préparation des projets clients.');
        $experienceItem30->setPosition(3);
        $experienceItem30->setPicto('⚙️');
        $experienceItem30->setCreatedAt(new \DateTime('2026-05-23 20:18:58'));
        $experienceItem30->setUpdatedAt(null);
        $experienceItem30->setTitle('Optimisation des processus internes');
        try {
            $experienceItem30->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem30);
        $this->addReference('experienceItem_43', $experienceItem30);

        $experienceItem31 = new ExperienceItem();
        $experienceItem31->setDetails('Création d’un outil pensé pour les équipes projet, développement et graphisme, facilitant la coordination et le suivi des réalisations.');
        $experienceItem31->setPosition(4);
        $experienceItem31->setPicto('🤝');
        $experienceItem31->setCreatedAt(new \DateTime('2026-05-23 20:18:58'));
        $experienceItem31->setUpdatedAt(null);
        $experienceItem31->setTitle('Développement orienté besoins métier');
        try {
            $experienceItem31->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem31);
        $this->addReference('experienceItem_44', $experienceItem31);

        $experienceItem32 = new ExperienceItem();
        $experienceItem32->setDetails('Étude des recherches utilisateurs et des mots-clés afin d’optimiser la structure des sites et améliorer leur visibilité.');
        $experienceItem32->setPosition(1);
        $experienceItem32->setPicto('🔍');
        $experienceItem32->setCreatedAt(new \DateTime('2026-05-23 20:22:53'));
        $experienceItem32->setUpdatedAt(null);
        $experienceItem32->setTitle('Analyse des usages et du référencement web');
        try {
            $experienceItem32->setExperience($this->getReference('experience_12', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem32);
        $this->addReference('experienceItem_45', $experienceItem32);

        $experienceItem33 = new ExperienceItem();
        $experienceItem33->setDetails('Rédaction de recommandations autour du contenu, du positionnement, de la concurrence et des mécaniques d’animation web.');
        $experienceItem33->setPosition(2);
        $experienceItem33->setPicto('🧩');
        $experienceItem33->setCreatedAt(new \DateTime('2026-05-23 20:22:53'));
        $experienceItem33->setUpdatedAt(null);
        $experienceItem33->setTitle('Accompagnement stratégique des projets digitaux');
        try {
            $experienceItem33->setExperience($this->getReference('experience_12', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem33);
        $this->addReference('experienceItem_46', $experienceItem33);

        $experienceItem34 = new ExperienceItem();
        $experienceItem34->setDetails('Participation à l’analyse des attentes utilisateurs pour orienter les choix fonctionnels et éditoriaux des futurs sites Internet.');
        $experienceItem34->setPosition(3);
        $experienceItem34->setPicto('🧠');
        $experienceItem34->setCreatedAt(new \DateTime('2026-05-23 20:22:53'));
        $experienceItem34->setUpdatedAt(null);
        $experienceItem34->setTitle('Études de marché et conseil client');
        try {
            $experienceItem34->setExperience($this->getReference('experience_12', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem34);
        $this->addReference('experienceItem_47', $experienceItem34);

        $experienceItem35 = new ExperienceItem();
        $experienceItem35->setDetails('Réalisation de zonings et réflexion sur l’organisation des contenus avant la phase de conception graphique.');
        $experienceItem35->setPosition(4);
        $experienceItem35->setPicto('✏️');
        $experienceItem35->setCreatedAt(new \DateTime('2026-05-23 20:22:53'));
        $experienceItem35->setUpdatedAt(null);
        $experienceItem35->setTitle('Préparation de l’architecture des interfaces');
        try {
            $experienceItem35->setExperience($this->getReference('experience_12', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem35);
        $this->addReference('experienceItem_48', $experienceItem35);

        $experienceItem36 = new ExperienceItem();
        $experienceItem36->setDetails('Travail de recherche autour des notions de symétrie et de similarité dans les familles d’ensembles, avec étude des relations entre attributs et graphes.');
        $experienceItem36->setPosition(1);
        $experienceItem36->setPicto('🔬');
        $experienceItem36->setCreatedAt(new \DateTime('2026-05-23 20:26:54'));
        $experienceItem36->setUpdatedAt(null);
        $experienceItem36->setTitle('Recherche en algorithmique et théorie des graphes');
        try {
            $experienceItem36->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem36);
        $this->addReference('experienceItem_49', $experienceItem36);

        $experienceItem37 = new ExperienceItem();
        $experienceItem37->setDetails('Développement et analyse de plusieurs algorithmes de calcul de similarité, dont une approche démontrée optimale sous certaines conditions.');
        $experienceItem37->setPosition(2);
        $experienceItem37->setPicto('⚙️');
        $experienceItem37->setCreatedAt(new \DateTime('2026-05-23 20:26:54'));
        $experienceItem37->setUpdatedAt(null);
        $experienceItem37->setTitle('Conception et optimisation d’algorithmes');
        try {
            $experienceItem37->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem37);
        $this->addReference('experienceItem_50', $experienceItem37);

        $experienceItem38 = new ExperienceItem();
        $experienceItem38->setDetails('Rédaction de deux articles de recherche à l’issue du stage, dont une publication présentée à la conférence CLA’05 en République tchèque.');
        $experienceItem38->setPosition(3);
        $experienceItem38->setPicto('📚');
        $experienceItem38->setCreatedAt(new \DateTime('2026-05-23 20:26:54'));
        $experienceItem38->setUpdatedAt(null);
        $experienceItem38->setTitle('Valorisation scientifique des travaux');
        try {
            $experienceItem38->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem38);
        $this->addReference('experienceItem_51', $experienceItem38);

        $experienceItem39 = new ExperienceItem();
        $experienceItem39->setDetails('Expérience de recherche mêlant modélisation, démonstration théorique et expérimentation algorithmique dans un contexte académique.');
        $experienceItem39->setPosition(4);
        $experienceItem39->setPicto('🧠');
        $experienceItem39->setCreatedAt(new \DateTime('2026-05-23 20:26:54'));
        $experienceItem39->setUpdatedAt(null);
        $experienceItem39->setTitle('Approche analytique et rigueur scientifique');
        try {
            $experienceItem39->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceItem39);
        $this->addReference('experienceItem_52', $experienceItem39);

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
