<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $project0 = new Project();
        $project0->setName('Fouille de données interactive par navigation');
        $project0->setPeriod('De avril 2004 à juillet 2004');
        $project0->setYear(2004);
        $project0->setDescription('Projet orienté recherche et structures de données complexes : modélisation et implémentation d\'une bibliothèque bas niveau interconnectée avec un outil de data-mining.<p class="text-center mt-5"><a class="btn btn-flat btn-nine waves-effect waves-light hvr-grow-shadow" href="https://docs.carolinenoyer.fr/pdf/cnoyer-rapportdestage-maitrise-2004.pdf" title="Rapport de stage"><i class="fas fa-paperclip mr-5" aria-hidden="true"></i> Rapport de stage</a></p>');
        $project0->setScreenshots('');
        $project0->setCategory('R&D');
        $project0->setTags('C++::Standard Template Library (STL)::Data Mining');
        $project0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project0->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project0->setLabel('datamining');
        $project0->setLogo('stagemaitrise.png');
        $manager->persist($project0);
        $this->addReference('project_8', $project0);

        $project1 = new Project();
        $project1->setName('Génération de sites Internet valides W3C');
        $project1->setPeriod('De novembre 2006 à avril 2007');
        $project1->setYear(2006);
        $project1->setDescription('Réalisation d\'un framework industriel pour la société Actifdesign permettant la génération automatisée et modulaire de plateformes web conformes aux strictes spécifications XHTML du W3C.');
        $project1->setScreenshots('');
        $project1->setCategory('R&D Pro');
        $project1->setTags('Java / J2EE::XML::XHTML Compliance');
        $project1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project1->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project1->setLabel('nevotec');
        $project1->setLogo('nevotec.png');
        $manager->persist($project1);
        $this->addReference('project_9', $project1);

        $project2 = new Project();
        $project2->setName('Génération de fichiers PDF haute définition');
        $project2->setPeriod('De février à novembre 2007');
        $project2->setYear(2007);
        $project2->setDescription('Conception d\'un framework d\'ingénierie pour la société Actifdesign. Développement du moteur métier pour générer dynamiquement des maquettes de produits imprimés HD.');
        $project2->setScreenshots('');
        $project2->setCategory('R&D Pro');
        $project2->setTags('Java (Métier)::XML (Stockage)::Librairies PDF');
        $project2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project2->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project2->setLabel('nevoprint');
        $project2->setLogo('nevoprint.png');
        $manager->persist($project2);
        $this->addReference('project_10', $project2);

        $project3 = new Project();
        $project3->setName('Création graphique et développement');
        $project3->setPeriod('Novembre 2007');
        $project3->setYear(2008);
        $project3->setDescription('Plateforme complète visant à valoriser les circuits de randonnées en Auvergne. Intégration de topo-guides enrichis par le biais de photographies terrain, de résumés techniques et d\'un système collaboratif d\'avis sur les parcours.');
        $project3->setScreenshots('website-randos.png');
        $project3->setCategory('Web & Dev');
        $project3->setTags('PHP::MySQL::HTML / CSS::Illustrator');
        $project3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project3->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project3->setLabel('randos');
        $project3->setLogo('randos.png');
        $manager->persist($project3);
        $this->addReference('project_11', $project3);

        $project4 = new Project();
        $project4->setName('Création graphique et Site Internet');
        $project4->setPeriod('Octobre 2010');
        $project4->setYear(2010);
        $project4->setDescription('Conception graphique globale et intégration du site Internet officiel de l\'association étudiante.');
        $project4->setScreenshots('website-teamtanesc.png');
        $project4->setCategory('Web & Dev');
        $project4->setTags('PHP::HTML/CSS');
        $project4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project4->setLabel('ttc');
        $project4->setLogo('ttc.png');
        $manager->persist($project4);
        $this->addReference('project_12', $project4);

        $project5 = new Project();
        $project5->setName('Création graphique');
        $project5->setPeriod('Depuis 2012');
        $project5->setYear(2012);
        $project5->setDescription('Réalisation de créations graphiques sur-mesure pour mon entourage : faire-parts personnalisés, cartes de visite professionnelles et identités visuelles.');
        $project5->setScreenshots('fairepart-gus.jpg::fairepart-elea.jpg::fairepart-cf.jpg::plu2cloud-logo-verti.png::plugeekit-logo-verti.png::tradee-logo-vert.png');
        $project5->setCategory('Print & Digital');
        $project5->setTags('Adobe Illustrator::Figma::Canva');
        $project5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project5->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project5->setLabel('creations');
        $project5->setLogo('nine.png');
        $manager->persist($project5);
        $this->addReference('project_13', $project5);

        $project6 = new Project();
        $project6->setName('Montage vidéo');
        $project6->setPeriod('Depuis 2014');
        $project6->setYear(2014);
        $project6->setDescription('Création et montage de formats vidéos rythmés pour des occasions particulières (mariages, anniversaires, fêtes). Travail sur le storytelling, le rythme et la colorimétrie.');
        $project6->setScreenshots('');
        $project6->setCategory('Vidéo');
        $project6->setTags('Adobe Premiere::iMovie');
        $project6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project6->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $project6->setLabel('montages');
        $project6->setLogo('nine.png');
        $manager->persist($project6);
        $this->addReference('project_14', $project6);

        $manager->flush();
    }
}
