<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
        $project0->setDescription('
Réalisation d\'une bibliothèque <b>C++</b> à l\'aide de la <b>STL</b> afin de l\'interfacer avec l\'outil de navigation.
<p class="center-align pt-20"><a class="btn btn-flat btn-nine waves-effect waves-light hvr-grow-shadow" href="https://web.archive.org/web/20230205074712/http://docs.carolinenoyer.fr/pdf/cnoyer-rapportdestage-maitrise-2004.pdf" title="Rapport de stage"><i class="fas fa-paperclip mr-5" aria-hidden="true"></i> Rapport de stage</a></p>');
        $project0->setScreenshots('');
        $project0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project0->setLabel('datamining');
        $project0->setLogo('stagemaitrise.png');
        $manager->persist($project0);
        $this->addReference('project_1', $project0);

        $project1 = new Project();
        $project1->setName('Génération de sites Internet valides W3C');
        $project1->setPeriod('De novembre 2006 à avril 2007');
        $project1->setYear(2006);
        $project1->setDescription('
<p><strong>Framework de génération de sites Internet permettant de produire des pages valides XHTML.</strong>
    <br>
    Projet professionnel réalisé pour la société Actifdesign.</p>
<ul class="missions">
    <li>Utilisation du langage <b>Java</b> pour la partie métier</li>
    <li>Utilisation de la spécification <b>J2ee</b> pour la génération des pages HTML</li>
    <li>Utilisation du langage <b>XML</b> pour le stockage des données</li>
</ul>');
        $project1->setScreenshots('');
        $project1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project1->setLabel('nevotec');
        $project1->setLogo('nevotec.png');
        $manager->persist($project1);
        $this->addReference('project_2', $project1);

        $project2 = new Project();
        $project2->setName('Génération de fichiers PDF haute définition');
        $project2->setPeriod('De février à novembre 2007');
        $project2->setYear(2007);
        $project2->setDescription('
<p><strong>Framework de génération de produits imprimés haute définition au format PDF.</strong>
    <br>
    Projet professionnel réalisé pour la société Actifdesign.</p>
<ul class="missions">
    <li>Utilisation du langage <b>Java</b> pour la partie métier</li>
    <li>Utilisation du langage <b>XML</b> pour le stockage des données</li>
    <li>Génération de documents PDF haute définition à l\'aide de bibliothèques Java</li>
</ul>');
        $project2->setScreenshots('');
        $project2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project2->setLabel('nevoprint');
        $project2->setLogo('nevoprint.png');
        $manager->persist($project2);
        $this->addReference('project_3', $project2);

        $project3 = new Project();
        $project3->setName('Création graphique et développement');
        $project3->setPeriod('Novembre 2007');
        $project3->setYear(2008);
        $project3->setDescription('Ce projet a pour but de valoriser les randonnées en Auvergne, en proposant des circuits de randonnées par le biais de photos, d\'un bref résumé et d\'avis sur les parcours.<br><br>
<em>Adobe Illustrator &bull; HTML &bull; CSS &bull; PHP &bull; MySQL</em>');
        $project3->setScreenshots('website-randos.png');
        $project3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project3->setLabel('randos');
        $project3->setLogo('randos.png');
        $manager->persist($project3);
        $this->addReference('project_4', $project3);

        $project4 = new Project();
        $project4->setName('Création graphique et Site Internet');
        $project4->setPeriod('Octobre 2010');
        $project4->setYear(2010);
        $project4->setDescription('Création graphique du site Internet du BDE TeamTanesC.
<br><br>
<em>Adobe Illustrator &bull; PHP &bull; HTML &bull; CSS</em>');
        $project4->setScreenshots('website-teamtanesc.png');
        $project4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project4->setLabel('ttc');
        $project4->setLogo('ttc.png');
        $manager->persist($project4);
        $this->addReference('project_5', $project4);

        $project5 = new Project();
        $project5->setName('Création graphique');
        $project5->setPeriod('Depuis 2012');
        $project5->setYear(2012);
        $project5->setDescription('Réalisation de créations graphiques pour mon entourage : faire-parts, cartes de visite, logos<br><br>
<em>Adobe Illustrator &bull; Figma &bull; Canva</em>');
        $project5->setScreenshots('fairepart-gus.jpg::fairepart-elea.jpg::fairepart-cf.jpg::plu2cloud-logo-verti.png::plugeekit-logo-verti.png::tradee-logo-vert.png');
        $project5->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project5->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project5->setLabel('creations');
        $project5->setLogo('nine.png');
        $manager->persist($project5);
        $this->addReference('project_6', $project5);

        $project6 = new Project();
        $project6->setName('Montage vidéo');
        $project6->setPeriod('Depuis 2014');
        $project6->setYear(2014);
        $project6->setDescription('Montage vidéo pour des occasions particulières (mariage, anniversaire...)
<br><br>
<em>Adobe Premiere &bull; iMovie</em>');
        $project6->setScreenshots('');
        $project6->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project6->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $project6->setLabel('montages');
        $project6->setLogo('nine.png');
        $manager->persist($project6);
        $this->addReference('project_7', $project6);

        $manager->flush();
    }
}
