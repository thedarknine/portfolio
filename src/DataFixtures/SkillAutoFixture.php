<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Entity\SkillType;
use App\DataFixtures\SkillTypeAutoFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SkillAutoFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $skill0 = new Skill();
        $skill0->setStartYear(2018);
        $skill0->setEndYear(2024);
        $skill0->setLevel(8);
        $skill0->setDisplay(true);
        $skill0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill0->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill0->setName('Jira');
        $skill0->setSlug('jira');
        $skill0->setLogo('jira.png');
        $skill0->setPosition(4);
        try {
            $skill0->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill0);
        $this->addReference('skill_56', $skill0);

        $skill1 = new Skill();
        $skill1->setStartYear(2018);
        $skill1->setEndYear(null);
        $skill1->setLevel(6);
        $skill1->setDisplay(false);
        $skill1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill1->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill1->setName('Agile');
        $skill1->setSlug('agile');
        $skill1->setLogo('agile.png');
        $skill1->setPosition(4);
        try {
            $skill1->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill1);
        $this->addReference('skill_57', $skill1);

        $skill2 = new Skill();
        $skill2->setStartYear(2017);
        $skill2->setEndYear(2022);
        $skill2->setLevel(7);
        $skill2->setDisplay(false);
        $skill2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill2->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill2->setName('Trello');
        $skill2->setSlug('trello');
        $skill2->setLogo('trello.png');
        $skill2->setPosition(4);
        try {
            $skill2->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill2);
        $this->addReference('skill_58', $skill2);

        $skill3 = new Skill();
        $skill3->setStartYear(2022);
        $skill3->setEndYear(2024);
        $skill3->setLevel(6);
        $skill3->setDisplay(false);
        $skill3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill3->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill3->setName('Product Disc');
        $skill3->setSlug('product-discovery');
        $skill3->setLogo('product-discovery.png');
        $skill3->setPosition(4);
        try {
            $skill3->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill3);
        $this->addReference('skill_59', $skill3);

        $skill4 = new Skill();
        $skill4->setStartYear(2018);
        $skill4->setEndYear(2024);
        $skill4->setLevel(6);
        $skill4->setDisplay(true);
        $skill4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill4->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill4->setName('Confluence');
        $skill4->setSlug('confluence');
        $skill4->setLogo('confluence.png');
        $skill4->setPosition(4);
        try {
            $skill4->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill4);
        $this->addReference('skill_60', $skill4);

        $skill5 = new Skill();
        $skill5->setStartYear(2018);
        $skill5->setEndYear(2022);
        $skill5->setLevel(7);
        $skill5->setDisplay(true);
        $skill5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill5->setUpdatedAt(new \DateTime('2026-05-25 07:38:10'));
        $skill5->setName('Miro');
        $skill5->setSlug('miro');
        $skill5->setLogo('miro.png');
        $skill5->setPosition(1);
        try {
            $skill5->setSkillType($this->getReference('skillType_16', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill5);
        $this->addReference('skill_61', $skill5);

        $skill6 = new Skill();
        $skill6->setStartYear(2020);
        $skill6->setEndYear(null);
        $skill6->setLevel(7);
        $skill6->setDisplay(true);
        $skill6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill6->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill6->setName('Figma');
        $skill6->setSlug('figma');
        $skill6->setLogo('figma.png');
        $skill6->setPosition(4);
        try {
            $skill6->setSkillType($this->getReference('skillType_17', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill6);
        $this->addReference('skill_62', $skill6);

        $skill7 = new Skill();
        $skill7->setStartYear(2018);
        $skill7->setEndYear(2021);
        $skill7->setLevel(6);
        $skill7->setDisplay(true);
        $skill7->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill7->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill7->setName('Moqups');
        $skill7->setSlug('moqups');
        $skill7->setLogo('moqups.png');
        $skill7->setPosition(4);
        try {
            $skill7->setSkillType($this->getReference('skillType_17', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill7);
        $this->addReference('skill_63', $skill7);

        $skill8 = new Skill();
        $skill8->setStartYear(2010);
        $skill8->setEndYear(2022);
        $skill8->setLevel(5);
        $skill8->setDisplay(true);
        $skill8->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill8->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill8->setName('Illustrator');
        $skill8->setSlug('illustrator');
        $skill8->setLogo('illustrator.png');
        $skill8->setPosition(4);
        try {
            $skill8->setSkillType($this->getReference('skillType_17', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill8);
        $this->addReference('skill_64', $skill8);

        $skill9 = new Skill();
        $skill9->setStartYear(2006);
        $skill9->setEndYear(2017);
        $skill9->setLevel(3);
        $skill9->setDisplay(true);
        $skill9->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill9->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill9->setName('Photoshop');
        $skill9->setSlug('photoshop');
        $skill9->setLogo('photoshop.png');
        $skill9->setPosition(4);
        try {
            $skill9->setSkillType($this->getReference('skillType_17', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill9);
        $this->addReference('skill_65', $skill9);

        $skill10 = new Skill();
        $skill10->setStartYear(2005);
        $skill10->setEndYear(null);
        $skill10->setLevel(8);
        $skill10->setDisplay(true);
        $skill10->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill10->setUpdatedAt(new \DateTime('2026-05-25 07:38:08'));
        $skill10->setName('MySQL');
        $skill10->setSlug('mysql');
        $skill10->setLogo('mysql.png');
        $skill10->setPosition(2);
        try {
            $skill10->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill10);
        $this->addReference('skill_66', $skill10);

        $skill11 = new Skill();
        $skill11->setStartYear(2017);
        $skill11->setEndYear(2022);
        $skill11->setLevel(7);
        $skill11->setDisplay(true);
        $skill11->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill11->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill11->setName('MongoDB');
        $skill11->setSlug('mongodb');
        $skill11->setLogo('mongodb.png');
        $skill11->setPosition(4);
        try {
            $skill11->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill11);
        $this->addReference('skill_67', $skill11);

        $skill12 = new Skill();
        $skill12->setStartYear(2022);
        $skill12->setEndYear(2023);
        $skill12->setLevel(2);
        $skill12->setDisplay(false);
        $skill12->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill12->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill12->setName('GraphDB');
        $skill12->setSlug('graphdb');
        $skill12->setLogo('graphdb.png');
        $skill12->setPosition(4);
        try {
            $skill12->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill12);
        $this->addReference('skill_68', $skill12);

        $skill13 = new Skill();
        $skill13->setStartYear(2017);
        $skill13->setEndYear(null);
        $skill13->setLevel(6);
        $skill13->setDisplay(true);
        $skill13->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill13->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill13->setName('Symfony');
        $skill13->setSlug('symfony');
        $skill13->setLogo('symfony.png');
        $skill13->setPosition(4);
        try {
            $skill13->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill13);
        $this->addReference('skill_69', $skill13);

        $skill14 = new Skill();
        $skill14->setStartYear(2005);
        $skill14->setEndYear(null);
        $skill14->setLevel(9);
        $skill14->setDisplay(true);
        $skill14->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill14->setUpdatedAt(new \DateTime('2026-05-25 07:38:10'));
        $skill14->setName('PHP');
        $skill14->setSlug('php');
        $skill14->setLogo('php.png');
        $skill14->setPosition(0);
        try {
            $skill14->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill14);
        $this->addReference('skill_70', $skill14);

        $skill15 = new Skill();
        $skill15->setStartYear(2018);
        $skill15->setEndYear(2025);
        $skill15->setLevel(6);
        $skill15->setDisplay(true);
        $skill15->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill15->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill15->setName('Python');
        $skill15->setSlug('python');
        $skill15->setLogo('python.png');
        $skill15->setPosition(4);
        try {
            $skill15->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill15);
        $this->addReference('skill_71', $skill15);

        $skill16 = new Skill();
        $skill16->setStartYear(2017);
        $skill16->setEndYear(2022);
        $skill16->setLevel(5);
        $skill16->setDisplay(true);
        $skill16->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill16->setUpdatedAt(new \DateTime('2026-07-04 18:07:28'));
        $skill16->setName('RabbitMQ');
        $skill16->setSlug('rabbitmq');
        $skill16->setLogo('rabbitmq.png');
        $skill16->setPosition(6);
        try {
            $skill16->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill16);
        $this->addReference('skill_72', $skill16);

        $skill17 = new Skill();
        $skill17->setStartYear(2010);
        $skill17->setEndYear(2021);
        $skill17->setLevel(6);
        $skill17->setDisplay(false);
        $skill17->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill17->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill17->setName('Webservices');
        $skill17->setSlug('webservices');
        $skill17->setLogo('webservices.png');
        $skill17->setPosition(7);
        try {
            $skill17->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill17);
        $this->addReference('skill_73', $skill17);

        $skill18 = new Skill();
        $skill18->setStartYear(2008);
        $skill18->setEndYear(2019);
        $skill18->setLevel(7);
        $skill18->setDisplay(false);
        $skill18->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill18->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill18->setName('MVC');
        $skill18->setSlug('mvc');
        $skill18->setLogo('mvc.png');
        $skill18->setPosition(8);
        try {
            $skill18->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill18);
        $this->addReference('skill_74', $skill18);

        $skill19 = new Skill();
        $skill19->setStartYear(2017);
        $skill19->setEndYear(2022);
        $skill19->setLevel(8);
        $skill19->setDisplay(false);
        $skill19->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill19->setUpdatedAt(new \DateTime('2026-05-25 07:37:20'));
        $skill19->setName('PHPStorm');
        $skill19->setSlug('phpstorm');
        $skill19->setLogo('phpstorm.png');
        $skill19->setPosition(10);
        try {
            $skill19->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill19);
        $this->addReference('skill_75', $skill19);

        $skill20 = new Skill();
        $skill20->setStartYear(2008);
        $skill20->setEndYear(2012);
        $skill20->setLevel(5);
        $skill20->setDisplay(false);
        $skill20->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill20->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill20->setName('Zend');
        $skill20->setSlug('zend');
        $skill20->setLogo('zend.png');
        $skill20->setPosition(11);
        try {
            $skill20->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill20);
        $this->addReference('skill_76', $skill20);

        $skill21 = new Skill();
        $skill21->setStartYear(2002);
        $skill21->setEndYear(2006);
        $skill21->setLevel(6);
        $skill21->setDisplay(false);
        $skill21->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill21->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill21->setName('C++');
        $skill21->setSlug('cplus');
        $skill21->setLogo('cplus.png');
        $skill21->setPosition(12);
        try {
            $skill21->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill21);
        $this->addReference('skill_77', $skill21);

        $skill22 = new Skill();
        $skill22->setStartYear(2004);
        $skill22->setEndYear(null);
        $skill22->setLevel(9);
        $skill22->setDisplay(true);
        $skill22->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill22->setUpdatedAt(new \DateTime('2026-05-25 08:08:41'));
        $skill22->setName('HTML');
        $skill22->setSlug('html');
        $skill22->setLogo('html.png');
        $skill22->setPosition(4);
        try {
            $skill22->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill22);
        $this->addReference('skill_78', $skill22);

        $skill23 = new Skill();
        $skill23->setStartYear(2004);
        $skill23->setEndYear(null);
        $skill23->setLevel(9);
        $skill23->setDisplay(true);
        $skill23->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill23->setUpdatedAt(new \DateTime('2026-07-04 18:07:23'));
        $skill23->setName('CSS');
        $skill23->setSlug('css');
        $skill23->setLogo('css.png');
        $skill23->setPosition(4);
        try {
            $skill23->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill23);
        $this->addReference('skill_79', $skill23);

        $skill24 = new Skill();
        $skill24->setStartYear(2016);
        $skill24->setEndYear(null);
        $skill24->setLevel(7);
        $skill24->setDisplay(false);
        $skill24->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill24->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill24->setName('Sass');
        $skill24->setSlug('sass');
        $skill24->setLogo('sass.png');
        $skill24->setPosition(4);
        try {
            $skill24->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill24);
        $this->addReference('skill_80', $skill24);

        $skill25 = new Skill();
        $skill25->setStartYear(2015);
        $skill25->setEndYear(null);
        $skill25->setLevel(8);
        $skill25->setDisplay(true);
        $skill25->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill25->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill25->setName('Twig');
        $skill25->setSlug('twig');
        $skill25->setLogo('twig.png');
        $skill25->setPosition(4);
        try {
            $skill25->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill25);
        $this->addReference('skill_81', $skill25);

        $skill26 = new Skill();
        $skill26->setStartYear(2018);
        $skill26->setEndYear(2022);
        $skill26->setLevel(4);
        $skill26->setDisplay(false);
        $skill26->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill26->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill26->setName('Webpack');
        $skill26->setSlug('webpack');
        $skill26->setLogo('webpack.png');
        $skill26->setPosition(4);
        try {
            $skill26->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill26);
        $this->addReference('skill_82', $skill26);

        $skill27 = new Skill();
        $skill27->setStartYear(2015);
        $skill27->setEndYear(2020);
        $skill27->setLevel(6);
        $skill27->setDisplay(true);
        $skill27->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill27->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill27->setName('jQuery');
        $skill27->setSlug('jquery');
        $skill27->setLogo('jquery.png');
        $skill27->setPosition(4);
        try {
            $skill27->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill27);
        $this->addReference('skill_83', $skill27);

        $skill28 = new Skill();
        $skill28->setStartYear(2015);
        $skill28->setEndYear(2018);
        $skill28->setLevel(5);
        $skill28->setDisplay(false);
        $skill28->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill28->setUpdatedAt(new \DateTime('2026-07-04 18:07:28'));
        $skill28->setName('Ajax');
        $skill28->setSlug('ajax');
        $skill28->setLogo('ajax.png');
        $skill28->setPosition(6);
        try {
            $skill28->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill28);
        $this->addReference('skill_84', $skill28);

        $skill29 = new Skill();
        $skill29->setStartYear(2016);
        $skill29->setEndYear(2021);
        $skill29->setLevel(7);
        $skill29->setDisplay(false);
        $skill29->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill29->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill29->setName('Materialize');
        $skill29->setSlug('materialize');
        $skill29->setLogo('materialize.png');
        $skill29->setPosition(7);
        try {
            $skill29->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill29);
        $this->addReference('skill_85', $skill29);

        $skill30 = new Skill();
        $skill30->setStartYear(2015);
        $skill30->setEndYear(2017);
        $skill30->setLevel(6);
        $skill30->setDisplay(false);
        $skill30->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill30->setUpdatedAt(new \DateTime('2026-05-25 07:37:19'));
        $skill30->setName('Bootstrap');
        $skill30->setSlug('bootstrap');
        $skill30->setLogo('bootstrap.png');
        $skill30->setPosition(8);
        try {
            $skill30->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill30);
        $this->addReference('skill_86', $skill30);

        $skill31 = new Skill();
        $skill31->setStartYear(2015);
        $skill31->setEndYear(null);
        $skill31->setLevel(7);
        $skill31->setDisplay(false);
        $skill31->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill31->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill31->setName('Responsive');
        $skill31->setSlug('responsive');
        $skill31->setLogo('responsive.png');
        $skill31->setPosition(10);
        try {
            $skill31->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill31);
        $this->addReference('skill_87', $skill31);

        $skill32 = new Skill();
        $skill32->setStartYear(2006);
        $skill32->setEndYear(2008);
        $skill32->setLevel(8);
        $skill32->setDisplay(false);
        $skill32->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill32->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill32->setName('xHTML/CSS');
        $skill32->setSlug('xhtmlcss');
        $skill32->setLogo('xhtmlcss.png');
        $skill32->setPosition(11);
        try {
            $skill32->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill32);
        $this->addReference('skill_88', $skill32);

        $skill33 = new Skill();
        $skill33->setStartYear(2005);
        $skill33->setEndYear(2017);
        $skill33->setLevel(5);
        $skill33->setDisplay(false);
        $skill33->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill33->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill33->setName('Apache');
        $skill33->setSlug('apache');
        $skill33->setLogo('apache.png');
        $skill33->setPosition(13);
        try {
            $skill33->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill33);
        $this->addReference('skill_89', $skill33);

        $skill34 = new Skill();
        $skill34->setStartYear(2015);
        $skill34->setEndYear(null);
        $skill34->setLevel(7);
        $skill34->setDisplay(true);
        $skill34->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill34->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill34->setName('Git');
        $skill34->setSlug('git');
        $skill34->setLogo('git.png');
        $skill34->setPosition(4);
        try {
            $skill34->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill34);
        $this->addReference('skill_90', $skill34);

        $skill35 = new Skill();
        $skill35->setStartYear(2015);
        $skill35->setEndYear(null);
        $skill35->setLevel(7);
        $skill35->setDisplay(true);
        $skill35->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill35->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill35->setName('Composer');
        $skill35->setSlug('composer');
        $skill35->setLogo('composer.png');
        $skill35->setPosition(4);
        try {
            $skill35->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill35);
        $this->addReference('skill_91', $skill35);

        $skill36 = new Skill();
        $skill36->setStartYear(2018);
        $skill36->setEndYear(null);
        $skill36->setLevel(4);
        $skill36->setDisplay(true);
        $skill36->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill36->setUpdatedAt(new \DateTime('2026-05-25 08:08:41'));
        $skill36->setName('Docker');
        $skill36->setSlug('docker');
        $skill36->setLogo('docker.png');
        $skill36->setPosition(3);
        try {
            $skill36->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill36);
        $this->addReference('skill_92', $skill36);

        $skill37 = new Skill();
        $skill37->setStartYear(2008);
        $skill37->setEndYear(2012);
        $skill37->setLevel(6);
        $skill37->setDisplay(false);
        $skill37->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill37->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill37->setName('SVN');
        $skill37->setSlug('svn');
        $skill37->setLogo('svn.png');
        $skill37->setPosition(4);
        try {
            $skill37->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill37);
        $this->addReference('skill_93', $skill37);

        $skill38 = new Skill();
        $skill38->setStartYear(2006);
        $skill38->setEndYear(null);
        $skill38->setLevel(8);
        $skill38->setDisplay(true);
        $skill38->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill38->setUpdatedAt(new \DateTime('2026-07-04 18:07:28'));
        $skill38->setName('MacOS X');
        $skill38->setSlug('macos');
        $skill38->setLogo('macos.png');
        $skill38->setPosition(5);
        try {
            $skill38->setSkillType($this->getReference('skillType_14', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill38);
        $this->addReference('skill_94', $skill38);

        $skill39 = new Skill();
        $skill39->setStartYear(2003);
        $skill39->setEndYear(null);
        $skill39->setLevel(7);
        $skill39->setDisplay(true);
        $skill39->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill39->setUpdatedAt(new \DateTime('2026-07-04 18:07:28'));
        $skill39->setName('Linux');
        $skill39->setSlug('linux');
        $skill39->setLogo('linux.png');
        $skill39->setPosition(6);
        try {
            $skill39->setSkillType($this->getReference('skillType_14', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill39);
        $this->addReference('skill_95', $skill39);

        $skill40 = new Skill();
        $skill40->setStartYear(1996);
        $skill40->setEndYear(2010);
        $skill40->setLevel(5);
        $skill40->setDisplay(false);
        $skill40->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill40->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill40->setName('Windows');
        $skill40->setSlug('windows');
        $skill40->setLogo('windows.png');
        $skill40->setPosition(7);
        try {
            $skill40->setSkillType($this->getReference('skillType_14', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill40);
        $this->addReference('skill_96', $skill40);

        $skill41 = new Skill();
        $skill41->setStartYear(2008);
        $skill41->setEndYear(2012);
        $skill41->setLevel(3);
        $skill41->setDisplay(false);
        $skill41->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill41->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill41->setName('Drupal');
        $skill41->setSlug('drupal');
        $skill41->setLogo('drupal.png');
        $skill41->setPosition(8);
        try {
            $skill41->setSkillType($this->getReference('skillType_14', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill41);
        $this->addReference('skill_97', $skill41);

        $skill42 = new Skill();
        $skill42->setStartYear(2019);
        $skill42->setEndYear(2022);
        $skill42->setLevel(3);
        $skill42->setDisplay(true);
        $skill42->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill42->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill42->setName('Kibana');
        $skill42->setSlug('kibana');
        $skill42->setLogo('kibana.png');
        $skill42->setPosition(11);
        try {
            $skill42->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill42);
        $this->addReference('skill_98', $skill42);

        $skill43 = new Skill();
        $skill43->setStartYear(2019);
        $skill43->setEndYear(2022);
        $skill43->setLevel(4);
        $skill43->setDisplay(false);
        $skill43->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill43->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill43->setName('Zendesk');
        $skill43->setSlug('zendesk');
        $skill43->setLogo('zendesk.png');
        $skill43->setPosition(12);
        try {
            $skill43->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill43);
        $this->addReference('skill_99', $skill43);

        $skill44 = new Skill();
        $skill44->setStartYear(2019);
        $skill44->setEndYear(2022);
        $skill44->setLevel(4);
        $skill44->setDisplay(true);
        $skill44->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill44->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill44->setName('MailChimp');
        $skill44->setSlug('mailchimp');
        $skill44->setLogo('mailchimp.png');
        $skill44->setPosition(10);
        try {
            $skill44->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill44);
        $this->addReference('skill_100', $skill44);

        $skill45 = new Skill();
        $skill45->setStartYear(2019);
        $skill45->setEndYear(null);
        $skill45->setLevel(5);
        $skill45->setDisplay(true);
        $skill45->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill45->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill45->setName('Postman');
        $skill45->setSlug('postman');
        $skill45->setLogo('postman.png');
        $skill45->setPosition(9);
        try {
            $skill45->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill45);
        $this->addReference('skill_101', $skill45);

        $skill46 = new Skill();
        $skill46->setStartYear(2018);
        $skill46->setEndYear(null);
        $skill46->setLevel(7);
        $skill46->setDisplay(false);
        $skill46->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill46->setUpdatedAt(new \DateTime('2026-07-04 18:07:28'));
        $skill46->setName('Slack');
        $skill46->setSlug('slack');
        $skill46->setLogo('slack.png');
        $skill46->setPosition(6);
        try {
            $skill46->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill46);
        $this->addReference('skill_102', $skill46);

        $skill47 = new Skill();
        $skill47->setStartYear(2022);
        $skill47->setEndYear(2024);
        $skill47->setLevel(4);
        $skill47->setDisplay(false);
        $skill47->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill47->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill47->setName('Teams');
        $skill47->setSlug('teams');
        $skill47->setLogo('teams.png');
        $skill47->setPosition(7);
        try {
            $skill47->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill47);
        $this->addReference('skill_103', $skill47);

        $skill48 = new Skill();
        $skill48->setStartYear(2002);
        $skill48->setEndYear(2006);
        $skill48->setLevel(8);
        $skill48->setDisplay(false);
        $skill48->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill48->setUpdatedAt(new \DateTime('2026-05-25 07:36:07'));
        $skill48->setName('LaTeX');
        $skill48->setSlug('latex');
        $skill48->setLogo('latex.png');
        $skill48->setPosition(8);
        try {
            $skill48->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill48);
        $this->addReference('skill_104', $skill48);

        $skill49 = new Skill();
        $skill49->setStartYear(2021);
        $skill49->setEndYear(null);
        $skill49->setLevel(6);
        $skill49->setDisplay(true);
        $skill49->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill49->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill49->setName('Tailwind');
        $skill49->setSlug('tailwind');
        $skill49->setLogo('tailwind.png');
        $skill49->setPosition(4);
        try {
            $skill49->setSkillType($this->getReference('skillType_15', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill49);
        $this->addReference('skill_105', $skill49);

        $skill50 = new Skill();
        $skill50->setStartYear(2025);
        $skill50->setEndYear(null);
        $skill50->setLevel(6);
        $skill50->setDisplay(true);
        $skill50->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill50->setUpdatedAt(new \DateTime('2026-05-25 08:08:30'));
        $skill50->setName('n8n');
        $skill50->setSlug('n8n');
        $skill50->setLogo('n8n.png');
        $skill50->setPosition(4);
        try {
            $skill50->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill50);
        $this->addReference('skill_106', $skill50);

        $skill51 = new Skill();
        $skill51->setStartYear(2025);
        $skill51->setEndYear(null);
        $skill51->setLevel(6);
        $skill51->setDisplay(true);
        $skill51->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill51->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill51->setName('NocoDB');
        $skill51->setSlug('nocodb');
        $skill51->setLogo('nocodb.png');
        $skill51->setPosition(4);
        try {
            $skill51->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill51);
        $this->addReference('skill_107', $skill51);

        $skill52 = new Skill();
        $skill52->setStartYear(2024);
        $skill52->setEndYear(null);
        $skill52->setLevel(7);
        $skill52->setDisplay(true);
        $skill52->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skill52->setUpdatedAt(new \DateTime('2026-05-25 08:09:01'));
        $skill52->setName('Nextcloud');
        $skill52->setSlug('nextcloud');
        $skill52->setLogo('nextcloud.png');
        $skill52->setPosition(4);
        try {
            $skill52->setSkillType($this->getReference('skillType_18', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill52);
        $this->addReference('skill_108', $skill52);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            SkillTypeAutoFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
