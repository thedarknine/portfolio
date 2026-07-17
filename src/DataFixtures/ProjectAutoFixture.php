<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectTag;
use App\DataFixtures\ProjectTagAutoFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectAutoFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $project0 = new Project();
        $project0->setPeriod('De avril 2004 à juillet 2004');
        $project0->setYear(2004);
        $project0->setDescription('<div>Projet orienté recherche et structures de données complexes : modélisation et implémentation d\'une bibliothèque bas niveau interconnectée avec un outil de data-mining.</div><div><a href="https://docs.carolinenoyer.fr/pdf/cnoyer-rapport-stage-maitrise-2004.pdf"><br>&nbsp;Rapport de stage</a></div>');
        $project0->setCategory('R&D');
        $project0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project0->setUpdatedAt(new \DateTime('2026-07-04 21:24:53'));
        $project0->setName('Fouille de données interactive par navigation');
        $project0->setSlug('datamining');
        $project0->setLogo('stagemaitrise.png');
        $project0->setPublished(true);
        try {
            $project0->addTag($this->getReference('projectTag_14', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project0->addTag($this->getReference('projectTag_15', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project0->addTag($this->getReference('projectTag_16', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project0);
        $this->addReference('project_8', $project0);

        $project1 = new Project();
        $project1->setPeriod('De novembre 2006 à avril 2007');
        $project1->setYear(2006);
        $project1->setDescription('<div>Réalisation d\'un framework industriel pour la société Actifdesign permettant la génération automatisée et modulaire de plateformes web conformes aux strictes spécifications XHTML du W3C.</div>');
        $project1->setCategory('R&D Pro');
        $project1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project1->setUpdatedAt(new \DateTime('2026-07-04 21:24:34'));
        $project1->setName('Génération de sites Internet valides W3C');
        $project1->setSlug('nevotec');
        $project1->setLogo('nevotec.png');
        $project1->setPublished(true);
        try {
            $project1->addTag($this->getReference('projectTag_11', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project1->addTag($this->getReference('projectTag_10', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project1->addTag($this->getReference('projectTag_13', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project1);
        $this->addReference('project_9', $project1);

        $project2 = new Project();
        $project2->setPeriod('De février à novembre 2007');
        $project2->setYear(2007);
        $project2->setDescription('<div>Conception d\'un framework d\'ingénierie pour la société Actifdesign. Développement du moteur métier pour générer dynamiquement des maquettes de produits imprimés haute définition.</div>');
        $project2->setCategory('R&D Pro');
        $project2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project2->setUpdatedAt(new \DateTime('2026-07-04 21:24:11'));
        $project2->setName('Génération de fichiers PDF HD');
        $project2->setSlug('nevoprint');
        $project2->setLogo('nevoprint.png');
        $project2->setPublished(true);
        try {
            $project2->addTag($this->getReference('projectTag_11', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project2->addTag($this->getReference('projectTag_10', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project2->addTag($this->getReference('projectTag_12', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project2);
        $this->addReference('project_10', $project2);

        $project3 = new Project();
        $project3->setPeriod('Novembre 2007');
        $project3->setYear(2008);
        $project3->setDescription('<div>Plateforme complète visant à valoriser les circuits de randonnées en Auvergne. Intégration de topo-guides enrichis par le biais de photographies terrain, de résumés techniques et d\'un système collaboratif d\'avis sur les parcours.</div>');
        $project3->setCategory('Web & Dev');
        $project3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project3->setUpdatedAt(new \DateTime('2026-07-04 21:19:28'));
        $project3->setName('Création graphique et développement');
        $project3->setSlug('randos');
        $project3->setLogo('randos.png');
        $project3->setPublished(true);
        try {
            $project3->addTag($this->getReference('projectTag_7', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project3->addTag($this->getReference('projectTag_9', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project3->addTag($this->getReference('projectTag_8', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project3->addTag($this->getReference('projectTag_4', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project3);
        $this->addReference('project_11', $project3);

        $project4 = new Project();
        $project4->setPeriod('Octobre 2010');
        $project4->setYear(2010);
        $project4->setDescription('<div>Conception graphique globale et intégration du site Internet officiel de l\'association étudiante.</div>');
        $project4->setCategory('Web & Dev');
        $project4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project4->setUpdatedAt(new \DateTime('2026-07-04 21:18:42'));
        $project4->setName('Création graphique et Site Internet');
        $project4->setSlug('ttc');
        $project4->setLogo('ttc.png');
        $project4->setPublished(true);
        try {
            $project4->addTag($this->getReference('projectTag_7', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project4->addTag($this->getReference('projectTag_8', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project4);
        $this->addReference('project_12', $project4);

        $project5 = new Project();
        $project5->setPeriod('Depuis 2012');
        $project5->setYear(2012);
        $project5->setDescription('<div>Réalisation de créations graphiques sur-mesure pour mon entourage : faire-parts personnalisés, cartes de visite professionnelles et identités visuelles.</div>');
        $project5->setCategory('Print & Digital');
        $project5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project5->setUpdatedAt(new \DateTime('2026-07-04 19:51:13'));
        $project5->setName('Création graphique');
        $project5->setSlug('creations');
        $project5->setLogo('nine.png');
        $project5->setPublished(true);
        try {
            $project5->addTag($this->getReference('projectTag_4', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project5->addTag($this->getReference('projectTag_5', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project5->addTag($this->getReference('projectTag_6', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project5);
        $this->addReference('project_13', $project5);

        $project6 = new Project();
        $project6->setPeriod('Depuis 2014');
        $project6->setYear(2014);
        $project6->setDescription('<div>Création et montage de formats vidéos rythmés pour des occasions particulières (mariages, anniversaires, fêtes). Travail sur le storytelling, le rythme et la colorimétrie.</div>');
        $project6->setCategory('Vidéo');
        $project6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project6->setUpdatedAt(new \DateTime('2026-07-04 19:50:58'));
        $project6->setName('Montage vidéo');
        $project6->setSlug('montages');
        $project6->setLogo('nine.png');
        $project6->setPublished(true);
        try {
            $project6->addTag($this->getReference('projectTag_1', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $project6->addTag($this->getReference('projectTag_3', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project6);
        $this->addReference('project_14', $project6);

        $project7 = new Project();
        $project7->setPeriod('Depuis 2012');
        $project7->setYear(2026);
        $project7->setDescription('<div>feaf</div>');
        $project7->setCategory('Print & Digital');
        $project7->setCreatedAt(new \DateTime('2026-07-05 18:16:13'));
        $project7->setUpdatedAt(new \DateTime('2026-07-05 18:16:27'));
        $project7->setName('Test');
        $project7->setSlug('test');
        $project7->setLogo('test.jpg');
        $project7->setPublished(true);
        try {
            $project7->addTag($this->getReference('projectTag_7', ProjectTag::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($project7);
        $this->addReference('project_36', $project7);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            ProjectTagAutoFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
