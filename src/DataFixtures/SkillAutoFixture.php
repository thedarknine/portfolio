<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Entity\SkillType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SkillAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $skill0 = new Skill();
        $skill0->setName('Jira');
        $skill0->setStartYear(2018);
        $skill0->setEndYear(2024);
        $skill0->setLevel(8);
        $skill0->setPosition(1);
        $skill0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill0->setLabel('jira');
        $skill0->setLogo('jira.png');
        try {
            $skill0->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill0);
        $this->addReference('skill_1', $skill0);

        $skill1 = new Skill();
        $skill1->setName('Agile');
        $skill1->setStartYear(2018);
        $skill1->setEndYear(null);
        $skill1->setLevel(6);
        $skill1->setPosition(2);
        $skill1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill1->setLabel('agile');
        $skill1->setLogo('agile.png');
        try {
            $skill1->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill1);
        $this->addReference('skill_2', $skill1);

        $skill2 = new Skill();
        $skill2->setName('Trello');
        $skill2->setStartYear(2017);
        $skill2->setEndYear(2022);
        $skill2->setLevel(7);
        $skill2->setPosition(3);
        $skill2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill2->setLabel('trello');
        $skill2->setLogo('trello.png');
        try {
            $skill2->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill2);
        $this->addReference('skill_3', $skill2);

        $skill3 = new Skill();
        $skill3->setName('Product Disc');
        $skill3->setStartYear(2022);
        $skill3->setEndYear(2024);
        $skill3->setLevel(6);
        $skill3->setPosition(4);
        $skill3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill3->setLabel('product-discovery');
        $skill3->setLogo('product-discovery.png');
        try {
            $skill3->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill3);
        $this->addReference('skill_4', $skill3);

        $skill4 = new Skill();
        $skill4->setName('Confluence');
        $skill4->setStartYear(2018);
        $skill4->setEndYear(2024);
        $skill4->setLevel(6);
        $skill4->setPosition(5);
        $skill4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill4->setLabel('confluence');
        $skill4->setLogo('confluence.png');
        try {
            $skill4->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill4);
        $this->addReference('skill_5', $skill4);

        $skill5 = new Skill();
        $skill5->setName('Miro');
        $skill5->setStartYear(2018);
        $skill5->setEndYear(2022);
        $skill5->setLevel(7);
        $skill5->setPosition(6);
        $skill5->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill5->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill5->setLabel('miro');
        $skill5->setLogo('miro.png');
        try {
            $skill5->setSkillType($this->getReference('skillType_7', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill5);
        $this->addReference('skill_6', $skill5);

        $skill6 = new Skill();
        $skill6->setName('Figma');
        $skill6->setStartYear(2020);
        $skill6->setEndYear(null);
        $skill6->setLevel(7);
        $skill6->setPosition(1);
        $skill6->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill6->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill6->setLabel('figma');
        $skill6->setLogo('figma.png');
        try {
            $skill6->setSkillType($this->getReference('skillType_8', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill6);
        $this->addReference('skill_7', $skill6);

        $skill7 = new Skill();
        $skill7->setName('Moqups');
        $skill7->setStartYear(2018);
        $skill7->setEndYear(2021);
        $skill7->setLevel(6);
        $skill7->setPosition(2);
        $skill7->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill7->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill7->setLabel('moqups');
        $skill7->setLogo('moqups.png');
        try {
            $skill7->setSkillType($this->getReference('skillType_8', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill7);
        $this->addReference('skill_8', $skill7);

        $skill8 = new Skill();
        $skill8->setName('Illustrator');
        $skill8->setStartYear(2010);
        $skill8->setEndYear(2022);
        $skill8->setLevel(5);
        $skill8->setPosition(3);
        $skill8->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill8->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill8->setLabel('illustrator');
        $skill8->setLogo('illustrator.png');
        try {
            $skill8->setSkillType($this->getReference('skillType_8', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill8);
        $this->addReference('skill_9', $skill8);

        $skill9 = new Skill();
        $skill9->setName('Photoshop');
        $skill9->setStartYear(2006);
        $skill9->setEndYear(2017);
        $skill9->setLevel(3);
        $skill9->setPosition(4);
        $skill9->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill9->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill9->setLabel('photoshop');
        $skill9->setLogo('photoshop.png');
        try {
            $skill9->setSkillType($this->getReference('skillType_8', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill9);
        $this->addReference('skill_10', $skill9);

        $skill10 = new Skill();
        $skill10->setName('MySQL');
        $skill10->setStartYear(2005);
        $skill10->setEndYear(null);
        $skill10->setLevel(8);
        $skill10->setPosition(1);
        $skill10->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill10->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill10->setLabel('mysql');
        $skill10->setLogo('mysql.png');
        try {
            $skill10->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill10);
        $this->addReference('skill_11', $skill10);

        $skill11 = new Skill();
        $skill11->setName('MongoDB');
        $skill11->setStartYear(2017);
        $skill11->setEndYear(2022);
        $skill11->setLevel(7);
        $skill11->setPosition(2);
        $skill11->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill11->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill11->setLabel('mongodb');
        $skill11->setLogo('mongodb.png');
        try {
            $skill11->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill11);
        $this->addReference('skill_12', $skill11);

        $skill12 = new Skill();
        $skill12->setName('GraphDB');
        $skill12->setStartYear(2022);
        $skill12->setEndYear(2023);
        $skill12->setLevel(2);
        $skill12->setPosition(3);
        $skill12->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill12->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill12->setLabel('graphdb');
        $skill12->setLogo('graphdb.png');
        try {
            $skill12->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill12);
        $this->addReference('skill_13', $skill12);

        $skill13 = new Skill();
        $skill13->setName('Symfony');
        $skill13->setStartYear(2017);
        $skill13->setEndYear(null);
        $skill13->setLevel(6);
        $skill13->setPosition(4);
        $skill13->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill13->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill13->setLabel('symfony');
        $skill13->setLogo('symfony.png');
        try {
            $skill13->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill13);
        $this->addReference('skill_14', $skill13);

        $skill14 = new Skill();
        $skill14->setName('PHP');
        $skill14->setStartYear(2005);
        $skill14->setEndYear(null);
        $skill14->setLevel(9);
        $skill14->setPosition(5);
        $skill14->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill14->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill14->setLabel('php');
        $skill14->setLogo('php.png');
        try {
            $skill14->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill14);
        $this->addReference('skill_15', $skill14);

        $skill15 = new Skill();
        $skill15->setName('Python');
        $skill15->setStartYear(2018);
        $skill15->setEndYear(2025);
        $skill15->setLevel(6);
        $skill15->setPosition(6);
        $skill15->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill15->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill15->setLabel('python');
        $skill15->setLogo('python.png');
        try {
            $skill15->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill15);
        $this->addReference('skill_16', $skill15);

        $skill16 = new Skill();
        $skill16->setName('RabbitMQ');
        $skill16->setStartYear(2017);
        $skill16->setEndYear(2022);
        $skill16->setLevel(5);
        $skill16->setPosition(7);
        $skill16->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill16->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill16->setLabel('rabbitmq');
        $skill16->setLogo('rabbitmq.png');
        try {
            $skill16->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill16);
        $this->addReference('skill_17', $skill16);

        $skill17 = new Skill();
        $skill17->setName('Webservices');
        $skill17->setStartYear(2010);
        $skill17->setEndYear(2021);
        $skill17->setLevel(6);
        $skill17->setPosition(8);
        $skill17->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill17->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill17->setLabel('webservices');
        $skill17->setLogo('webservices.png');
        try {
            $skill17->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill17);
        $this->addReference('skill_18', $skill17);

        $skill18 = new Skill();
        $skill18->setName('MVC');
        $skill18->setStartYear(2008);
        $skill18->setEndYear(2019);
        $skill18->setLevel(7);
        $skill18->setPosition(9);
        $skill18->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill18->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill18->setLabel('mvc');
        $skill18->setLogo('mvc.png');
        try {
            $skill18->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill18);
        $this->addReference('skill_19', $skill18);

        $skill19 = new Skill();
        $skill19->setName('PHPStorm');
        $skill19->setStartYear(2017);
        $skill19->setEndYear(2022);
        $skill19->setLevel(8);
        $skill19->setPosition(10);
        $skill19->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill19->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill19->setLabel('phpstorm');
        $skill19->setLogo('phpstorm.png');
        try {
            $skill19->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill19);
        $this->addReference('skill_20', $skill19);

        $skill20 = new Skill();
        $skill20->setName('Zend');
        $skill20->setStartYear(2008);
        $skill20->setEndYear(2012);
        $skill20->setLevel(5);
        $skill20->setPosition(11);
        $skill20->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill20->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill20->setLabel('zend');
        $skill20->setLogo('zend.png');
        try {
            $skill20->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill20);
        $this->addReference('skill_21', $skill20);

        $skill21 = new Skill();
        $skill21->setName('C++');
        $skill21->setStartYear(2002);
        $skill21->setEndYear(2006);
        $skill21->setLevel(6);
        $skill21->setPosition(12);
        $skill21->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill21->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill21->setLabel('cplus');
        $skill21->setLogo('cplus.png');
        try {
            $skill21->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill21);
        $this->addReference('skill_22', $skill21);

        $skill22 = new Skill();
        $skill22->setName('HTML');
        $skill22->setStartYear(2004);
        $skill22->setEndYear(null);
        $skill22->setLevel(9);
        $skill22->setPosition(1);
        $skill22->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill22->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill22->setLabel('html');
        $skill22->setLogo('html.png');
        try {
            $skill22->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill22);
        $this->addReference('skill_23', $skill22);

        $skill23 = new Skill();
        $skill23->setName('CSS');
        $skill23->setStartYear(2004);
        $skill23->setEndYear(null);
        $skill23->setLevel(9);
        $skill23->setPosition(2);
        $skill23->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill23->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill23->setLabel('css');
        $skill23->setLogo('css.png');
        try {
            $skill23->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill23);
        $this->addReference('skill_24', $skill23);

        $skill24 = new Skill();
        $skill24->setName('Sass');
        $skill24->setStartYear(2016);
        $skill24->setEndYear(null);
        $skill24->setLevel(7);
        $skill24->setPosition(3);
        $skill24->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill24->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill24->setLabel('sass');
        $skill24->setLogo('sass.png');
        try {
            $skill24->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill24);
        $this->addReference('skill_25', $skill24);

        $skill25 = new Skill();
        $skill25->setName('Twig');
        $skill25->setStartYear(2015);
        $skill25->setEndYear(null);
        $skill25->setLevel(8);
        $skill25->setPosition(4);
        $skill25->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill25->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill25->setLabel('twig');
        $skill25->setLogo('twig.png');
        try {
            $skill25->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill25);
        $this->addReference('skill_26', $skill25);

        $skill26 = new Skill();
        $skill26->setName('Webpack');
        $skill26->setStartYear(2018);
        $skill26->setEndYear(2022);
        $skill26->setLevel(4);
        $skill26->setPosition(5);
        $skill26->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill26->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill26->setLabel('webpack');
        $skill26->setLogo('webpack.png');
        try {
            $skill26->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill26);
        $this->addReference('skill_27', $skill26);

        $skill27 = new Skill();
        $skill27->setName('jQuery');
        $skill27->setStartYear(2015);
        $skill27->setEndYear(2020);
        $skill27->setLevel(6);
        $skill27->setPosition(6);
        $skill27->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill27->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill27->setLabel('jquery');
        $skill27->setLogo('jquery.png');
        try {
            $skill27->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill27);
        $this->addReference('skill_28', $skill27);

        $skill28 = new Skill();
        $skill28->setName('Ajax');
        $skill28->setStartYear(2015);
        $skill28->setEndYear(2018);
        $skill28->setLevel(5);
        $skill28->setPosition(7);
        $skill28->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill28->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill28->setLabel('ajax');
        $skill28->setLogo('ajax.png');
        try {
            $skill28->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill28);
        $this->addReference('skill_29', $skill28);

        $skill29 = new Skill();
        $skill29->setName('Materialize');
        $skill29->setStartYear(2016);
        $skill29->setEndYear(2021);
        $skill29->setLevel(7);
        $skill29->setPosition(8);
        $skill29->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill29->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill29->setLabel('materialize');
        $skill29->setLogo('materialize.png');
        try {
            $skill29->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill29);
        $this->addReference('skill_30', $skill29);

        $skill30 = new Skill();
        $skill30->setName('Bootstrap');
        $skill30->setStartYear(2015);
        $skill30->setEndYear(2017);
        $skill30->setLevel(6);
        $skill30->setPosition(9);
        $skill30->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill30->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill30->setLabel('bootstrap');
        $skill30->setLogo('bootstrap.png');
        try {
            $skill30->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill30);
        $this->addReference('skill_31', $skill30);

        $skill31 = new Skill();
        $skill31->setName('Responsive');
        $skill31->setStartYear(2015);
        $skill31->setEndYear(null);
        $skill31->setLevel(7);
        $skill31->setPosition(10);
        $skill31->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill31->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill31->setLabel('responsive');
        $skill31->setLogo('responsive.png');
        try {
            $skill31->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill31);
        $this->addReference('skill_32', $skill31);

        $skill32 = new Skill();
        $skill32->setName('xHTML/CSS');
        $skill32->setStartYear(2006);
        $skill32->setEndYear(2008);
        $skill32->setLevel(8);
        $skill32->setPosition(11);
        $skill32->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill32->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill32->setLabel('xhtmlcss');
        $skill32->setLogo('xhtmlcss.png');
        try {
            $skill32->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill32);
        $this->addReference('skill_33', $skill32);

        $skill33 = new Skill();
        $skill33->setName('Apache');
        $skill33->setStartYear(2005);
        $skill33->setEndYear(2017);
        $skill33->setLevel(5);
        $skill33->setPosition(1);
        $skill33->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill33->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill33->setLabel('apache');
        $skill33->setLogo('apache.png');
        try {
            $skill33->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill33);
        $this->addReference('skill_34', $skill33);

        $skill34 = new Skill();
        $skill34->setName('Git');
        $skill34->setStartYear(2015);
        $skill34->setEndYear(null);
        $skill34->setLevel(7);
        $skill34->setPosition(2);
        $skill34->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill34->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill34->setLabel('git');
        $skill34->setLogo('git.png');
        try {
            $skill34->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill34);
        $this->addReference('skill_35', $skill34);

        $skill35 = new Skill();
        $skill35->setName('Composer');
        $skill35->setStartYear(2015);
        $skill35->setEndYear(null);
        $skill35->setLevel(7);
        $skill35->setPosition(3);
        $skill35->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill35->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill35->setLabel('composer');
        $skill35->setLogo('composer.png');
        try {
            $skill35->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill35);
        $this->addReference('skill_36', $skill35);

        $skill36 = new Skill();
        $skill36->setName('Docker');
        $skill36->setStartYear(2018);
        $skill36->setEndYear(null);
        $skill36->setLevel(4);
        $skill36->setPosition(4);
        $skill36->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill36->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill36->setLabel('docker');
        $skill36->setLogo('docker.png');
        try {
            $skill36->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill36);
        $this->addReference('skill_37', $skill36);

        $skill37 = new Skill();
        $skill37->setName('SVN');
        $skill37->setStartYear(2008);
        $skill37->setEndYear(2012);
        $skill37->setLevel(6);
        $skill37->setPosition(5);
        $skill37->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill37->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill37->setLabel('svn');
        $skill37->setLogo('svn.png');
        try {
            $skill37->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill37);
        $this->addReference('skill_38', $skill37);

        $skill38 = new Skill();
        $skill38->setName('MacOS X');
        $skill38->setStartYear(2006);
        $skill38->setEndYear(null);
        $skill38->setLevel(8);
        $skill38->setPosition(6);
        $skill38->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill38->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill38->setLabel('macos');
        $skill38->setLogo('mac.png');
        try {
            $skill38->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill38);
        $this->addReference('skill_39', $skill38);

        $skill39 = new Skill();
        $skill39->setName('Linux');
        $skill39->setStartYear(2003);
        $skill39->setEndYear(null);
        $skill39->setLevel(7);
        $skill39->setPosition(7);
        $skill39->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill39->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill39->setLabel('linux');
        $skill39->setLogo('linux.png');
        try {
            $skill39->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill39);
        $this->addReference('skill_40', $skill39);

        $skill40 = new Skill();
        $skill40->setName('Windows');
        $skill40->setStartYear(1996);
        $skill40->setEndYear(2010);
        $skill40->setLevel(5);
        $skill40->setPosition(8);
        $skill40->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill40->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill40->setLabel('windows');
        $skill40->setLogo('windows.png');
        try {
            $skill40->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill40);
        $this->addReference('skill_41', $skill40);

        $skill41 = new Skill();
        $skill41->setName('Drupal');
        $skill41->setStartYear(2008);
        $skill41->setEndYear(2012);
        $skill41->setLevel(3);
        $skill41->setPosition(9);
        $skill41->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill41->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill41->setLabel('drupal');
        $skill41->setLogo('drupal.png');
        try {
            $skill41->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill41);
        $this->addReference('skill_42', $skill41);

        $skill42 = new Skill();
        $skill42->setName('Kibana');
        $skill42->setStartYear(2019);
        $skill42->setEndYear(2022);
        $skill42->setLevel(3);
        $skill42->setPosition(11);
        $skill42->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill42->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill42->setLabel('kibana');
        $skill42->setLogo('kibana.png');
        try {
            $skill42->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill42);
        $this->addReference('skill_43', $skill42);

        $skill43 = new Skill();
        $skill43->setName('Zendesk');
        $skill43->setStartYear(2019);
        $skill43->setEndYear(2022);
        $skill43->setLevel(4);
        $skill43->setPosition(12);
        $skill43->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill43->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill43->setLabel('zendesk');
        $skill43->setLogo('zendesk.png');
        try {
            $skill43->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill43);
        $this->addReference('skill_44', $skill43);

        $skill44 = new Skill();
        $skill44->setName('MailChimp');
        $skill44->setStartYear(2019);
        $skill44->setEndYear(2022);
        $skill44->setLevel(4);
        $skill44->setPosition(10);
        $skill44->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill44->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill44->setLabel('mailchimp');
        $skill44->setLogo('mailchimp.png');
        try {
            $skill44->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill44);
        $this->addReference('skill_45', $skill44);

        $skill45 = new Skill();
        $skill45->setName('Postman');
        $skill45->setStartYear(2019);
        $skill45->setEndYear(null);
        $skill45->setLevel(5);
        $skill45->setPosition(13);
        $skill45->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill45->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill45->setLabel('postman');
        $skill45->setLogo('postman.png');
        try {
            $skill45->setSkillType($this->getReference('skillType_5', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill45);
        $this->addReference('skill_46', $skill45);

        $skill46 = new Skill();
        $skill46->setName('Slack');
        $skill46->setStartYear(2018);
        $skill46->setEndYear(null);
        $skill46->setLevel(7);
        $skill46->setPosition(7);
        $skill46->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill46->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill46->setLabel('slack');
        $skill46->setLogo('slack.png');
        try {
            $skill46->setSkillType($this->getReference('skillType_9', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill46);
        $this->addReference('skill_47', $skill46);

        $skill47 = new Skill();
        $skill47->setName('Teams');
        $skill47->setStartYear(2022);
        $skill47->setEndYear(2024);
        $skill47->setLevel(4);
        $skill47->setPosition(8);
        $skill47->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill47->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill47->setLabel('teams');
        $skill47->setLogo('teams.png');
        try {
            $skill47->setSkillType($this->getReference('skillType_9', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill47);
        $this->addReference('skill_48', $skill47);

        $skill48 = new Skill();
        $skill48->setName('LaTeX');
        $skill48->setStartYear(2002);
        $skill48->setEndYear(2006);
        $skill48->setLevel(8);
        $skill48->setPosition(9);
        $skill48->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill48->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill48->setLabel('latex');
        $skill48->setLogo('latex.png');
        try {
            $skill48->setSkillType($this->getReference('skillType_3', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill48);
        $this->addReference('skill_49', $skill48);

        $skill49 = new Skill();
        $skill49->setName('Tailwind');
        $skill49->setStartYear(2021);
        $skill49->setEndYear(null);
        $skill49->setLevel(6);
        $skill49->setPosition(5);
        $skill49->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill49->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skill49->setLabel('tailwind');
        $skill49->setLogo('tailwind.png');
        try {
            $skill49->setSkillType($this->getReference('skillType_4', SkillType::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($skill49);
        $this->addReference('skill_50', $skill49);

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
}
