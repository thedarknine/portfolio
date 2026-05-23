<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Company;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $experience0 = new Experience();
        $experience0->setTitle('Stagiaire en milieu de recherche');
        $experience0->setSubtitle('Étude algorithmique autour des symétries dans les familles d’ensembles');
        $experience0->setSummary(null);
        $experience0->setDescription('
<p>Sujet : <em>Symétrie dans les familles d\'ensembles : une étude algorithmique</em></p>
<p class="text-center pt1"><a class="btn waves-effect btn-nine hvr-grow-shadow grey darken-1" href="http://docs.carolinenoyer.fr/pdf/cnoyer-rapportdestage-dea-2005.pdf" target="_blank" title="Téléchargez le rapport de stage"><i class="fas fa-paperclip" aria-hidden="true"></i> Rapport de stage</a></p>
<p class="text-justify pt5 text-09"><i>Résumé : </i>On souhaite définir la notion de symétrie entre attributs dans une famille d\'ensembles. Une première réponse a été proposée par R. Medina et L. Nourine à travers la notion de clones. Deux attributs sont clones s\'ils sont interchangeables dans les ensembles de la famille. Ici, on s\'intéresse au degré de symétrie entre deux attributs : la similitude. Cette mesure est représentée dans une matrice indiquant pour tout couple d\'attributs, la "distance" qui les sépare. Différents algorithmes de calcul de cette similitude sont proposés, et, sous certaines conditions, l\'un d\'entre eux est optimal. Enfin, nous montrons le lien qui existe entre attributs clones et sommets jumeaux d\'un graphe.</p>
<div class="pt5 mb1">
    <p><strong class="">Publication</strong></p>
    <div class="bordered-grey ml2 pl1">
         <p class="text-justify">Deux articles de recherche ont été rédigés à l\'issue de ce stage dont un, <strong>"Efficient algorithms for clone items detection"</strong>, publié pour la conférence <em>CLA\'05 (Concept Lattices and Their Applications) - République Tchèque</em></p>
         <p class="mt0 mr2"><a class="btn waves-effect btn-nine hvr-grow-shadow grey darken-1" href="http://docs.carolinenoyer.fr/pdf/article-clones-cla05.pdf" target="_blank" title="Efficient algorithms for clone items detection"><i class="fas fa-paperclip" aria-hidden="true"></i> Article publié</a></p>
    </div>
</div>');
        $experience0->setStartDate(new \DateTime('2005-02-01 00:00:00'));
        $experience0->setEndDate(new \DateTime('2005-07-31 00:00:00'));
        $experience0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience0->setUpdatedAt(new \DateTime('2026-05-23 20:25:20'));
        $experience0->setLabel('stage-limos');
        try {
            $experience0->setCompany($this->getReference('company_9', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience0->addSkill($this->getReference('skill_77', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience0->addSkill($this->getReference('skill_104', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience0);
        $this->addReference('experience_11', $experience0);

        $experience1 = new Experience();
        $experience1->setTitle('Stagiaire études de projets Internet');
        $experience1->setSubtitle('Analyse web et accompagnement stratégique digital');
        $experience1->setSummary(null);
        $experience1->setDescription('
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Analyse de la demande des internautes selon des mots-clés afin d\'optimiser la structure du site Internet et son référencement</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Rédaction de recommandations pour la restructuration de sites, la modiﬁcation du contenu, l\'étude de concurrence, ou encore la mise en place d\'une stratégie (animations, jeux, ...)</span></li>
</ul>
<p class="text-justify pt5 text-09"><i>Résumé : </i>Le domaine de la communication sur Internet est très complexe. 
Dans le contexte de la société Periscope, le travail d\'un chef de projet va au-delà de la simple étude d\'architecture du site et du choix de technologies, 
il s\'étend vers l\'étude afin de pouvoir apporter un réel conseil aux clients. Ainsi, mon travail s\'est orienté en majorité vers l\'étude de marchés. 
Ce type d\'analyse doit pouvoir permettre de savoir quels sont les éléments à mettre en avant sur le site, aussi bien au niveau fonctionnel qu\'au niveau des produits. 
Une fois l\'étude finalisée, on va pouvoir rédiger des recommandations auprès du client et lui permettre de bénéficier de la réalisation d\'un site dont le contenu sera en adéquation avec la demande actuelle. 
L\'une des dernières étapes avant la mise en production consiste à réaliser un zoning des différentes pages du site, c\'est-à-dire préciser à quel endroit se situeront les éléments, 
ce n\'est qu\'ensuite que le directeur artistique pourra réaliser la maquette du site.</p>');
        $experience1->setStartDate(new \DateTime('2006-02-01 00:00:00'));
        $experience1->setEndDate(new \DateTime('2006-06-30 00:00:00'));
        $experience1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience1->setUpdatedAt(new \DateTime('2026-05-23 20:21:05'));
        $experience1->setLabel('stage-periscope');
        try {
            $experience1->setCompany($this->getReference('company_10', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experience1);
        $this->addReference('experience_12', $experience1);

        $experience2 = new Experience();
        $experience2->setTitle('Stagiaire analyse et développement');
        $experience2->setSubtitle('Conception d’un assistant de rédaction documentaire');
        $experience2->setSummary(null);
        $experience2->setDescription('
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Conception et réalisation d\'un assistant pour la rédaction des cahiers des charges</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Génération de fichiers PDF via l\'interface à partir de sources <em>LaTeX</em></span></li>
</ul>
<p class="text-center pt1"><a class="btn waves-effect btn-nine hvr-grow-shadow grey darken-1" href="http://docs.carolinenoyer.fr/pdf/cnoyer-rapportdestage-m2pro-2006.pdf" target="_blank" title="Téléchargez le rapport de stage"><i class="fas fa-paperclip" aria-hidden="true"></i> Rapport de stage</a></p>
<p class="text-justify pt5 text-09"><i>Résumé : </i>La société Actifdesign souhaite donc développer des solutions de catalogues en ligne ou des solutions de e-commerce. L\'inconvénient de la mise en place de tels projets réside dans la rédaction du cahier des charges, devant être remis rapidement au client. Afin de réduire les temps de rédaction, sans en perdre le sérieux ni la pertinence, elle souhaite factoriser au maximum les parties communes entre les différents cahiers des charges. En effet, ce type de travail passe très souvent par une phase de copier/coller intempestifs !<br>
Ce projet nécessite donc plusieurs documents : le cahier des charges, le devis pour le client, les fiches navettes destinées aux développeurs (contenant les modules à installer et les délais à respecter), ainsi que celle pour le graphiste.<br>
Cet outil doit donc proposer une rédaction semi-automatique de ces documents à partir de questionnaires précis. Ainsi, mon projet permet de répondre à la mise en place d\'une organisation du travail tout en respectant la contrainte de coût.</p>');
        $experience2->setStartDate(new \DateTime('2006-07-01 00:00:00'));
        $experience2->setEndDate(new \DateTime('2006-09-30 00:00:00'));
        $experience2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience2->setUpdatedAt(new \DateTime('2026-05-23 20:17:18'));
        $experience2->setLabel('stage-actifdesign');
        try {
            $experience2->setCompany($this->getReference('company_11', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience2->addSkill($this->getReference('skill_65', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience2->addSkill($this->getReference('skill_66', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience2->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience2->addSkill($this->getReference('skill_88', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience2->addSkill($this->getReference('skill_95', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience2);
        $this->addReference('experience_13', $experience2);

        $experience3 = new Experience();
        $experience3->setTitle('Développeur web');
        $experience3->setSubtitle('Développement de sites Internet et de solutions innovantes');
        $experience3->setSummary(null);
        $experience3->setDescription('
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Rédaction de cahiers des charges et planification de projets</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement web</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Intégration de sites valides aux normes du <b>W3C</b></span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement de l\'outil &nbsp;<a href="http://www.carolinenoyer.fr/projets#nevotec" title="NevoTec">NevoTec</a></span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement de l\'outil &nbsp;<a href="http://www.carolinenoyer.fr/projets#nevoprint" title="NevoPrint">NevoPrint</a></span></li>
</ul>');
        $experience3->setStartDate(new \DateTime('2006-10-01 00:00:00'));
        $experience3->setEndDate(new \DateTime('2008-07-15 00:00:00'));
        $experience3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience3->setUpdatedAt(new \DateTime('2026-05-23 20:13:46'));
        $experience3->setLabel('dev-web');
        try {
            $experience3->setCompany($this->getReference('company_11', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience3->addSkill($this->getReference('skill_65', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience3->addSkill($this->getReference('skill_66', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience3->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience3->addSkill($this->getReference('skill_88', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience3->addSkill($this->getReference('skill_89', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience3->addSkill($this->getReference('skill_95', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience3);
        $this->addReference('experience_14', $experience3);

        $experience4 = new Experience();
        $experience4->setTitle('Ingénieur de développement');
        $experience4->setSubtitle('Développement de l\'outil Cadenza');
        $experience4->setSummary(null);
        $experience4->setDescription('
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement d\'une plate-forme interne de gestion et de suivi de projets</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Mise en place de solutions afin de faciliter le travail en équipe</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Maintenance du site de l\'entreprise et du site support produits</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Sécurisation du logiciel <a href="https://www.allegorithmic.com/substance" target="_blank"><strong>Substance</strong></a> développé par la société</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Administration système pour des postes sous Windows XP, Windows Seven, Linux Fedora</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Administration réseau : gestion du firewall <strong>MonoWall</strong>, gestion de disques en partage <strong>FreeNAS</strong></span></li>
</ul>');
        $experience4->setStartDate(new \DateTime('2008-07-16 00:00:00'));
        $experience4->setEndDate(new \DateTime('2012-04-30 00:00:00'));
        $experience4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience4->setLabel('inge-dev');
        try {
            $experience4->setCompany($this->getReference('company_12', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience4->addSkill($this->getReference('skill_64', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_65', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_66', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_74', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_76', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_88', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_93', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_95', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_96', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience4->addSkill($this->getReference('skill_97', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience4);
        $this->addReference('experience_15', $experience4);

        $experience5 = new Experience();
        $experience5->setTitle('Développeur web fullstack');
        $experience5->setSubtitle('Conception et développement du projet PREV@PASS');
        $experience5->setSummary(null);
        $experience5->setDescription('
<p class="text-justify pt5">Le Projet <a class="" href="https://www.sante-auvergne.fr/" title="PREVAPASS" target="_blank"><strong>PREV@PASS</strong></a> (Parcours Accompagné Soins et Santé) permet une prise en charge coordonnée des patients dans le cadre d\'une médecine de parcours, incluant la prévention et l\'éducation thérapeutique du patient. Ce nouveau système d\'information vise à mieux synchroniser les actions de tous les professionnels du médical et du médico-social au profit de la prise en charge de qualité et sécurisée du patient.</p>
<p class="text-center"><a class="" href="https://www.sante-auvergne.fr/" title="Site Prev@PASS" target="_blank"><img src="http://carolinenoyer.fr/images/company/logo_prevapass.jpg" class="rounded responsive-img hvr-grow-shadow image-btn" alt="Prev@PASS"></a></p>
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Création graphique de l\'interface</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement front-end</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement back-end, sur une architecture MVC utilisant le micro-framework <b>Silex</b> (basé sur <b>Symfony2</b>)</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Mise en place de webservices</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i><span>Scripts pour l\'automatisation des tâches</span></li>
</ul>');
        $experience5->setStartDate(new \DateTime('2015-05-04 00:00:00'));
        $experience5->setEndDate(new \DateTime('2017-07-16 00:00:00'));
        $experience5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience5->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience5->setLabel('dev-mfpdd');
        try {
            $experience5->setCompany($this->getReference('company_13', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience5->addSkill($this->getReference('skill_66', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_71', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_73', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_74', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_78', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_79', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_81', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_83', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_84', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_86', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_87', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_90', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience5->addSkill($this->getReference('skill_91', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience5);
        $this->addReference('experience_16', $experience5);

        $experience6 = new Experience();
        $experience6->setTitle('Développeur PHP Senior');
        $experience6->setSubtitle('Conception et développement des produits Coffreo Pro');
        $experience6->setSummary(null);
        $experience6->setDescription('
<p class="text-justify pt5"><a class="" href="http://www.coffreo.biz/" title="Coffreo" target="_blank"><strong>COFFREO</strong></a>, acteur majeur de la dématérialisation RH et du coffre-fort numérique, sécurise et rend agile la relation entre employeurs et leurs salariés là où elle représente un fort enjeu pour leur activité, et accompagne les salariés quelle que soit leur situation afin de faciliter l’accès à l’emploi.</p>
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Squad leader d\'un nouveau produit pour l\'offre Coffreo Pro (gestion de projet)</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Analyse et rédaction des spécifications techniques du produit</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Suivi des développements de l\'équipe et synchronisation avec le chef de projets</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Support clients du produit</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Développement Back &amp; API sur le Framework Symfony sur le principe de micro services</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Création d\'un service supplémentaire basé sur API-Platform</span></li>
</ul>');
        $experience6->setStartDate(new \DateTime('2017-07-17 00:00:00'));
        $experience6->setEndDate(new \DateTime('2019-11-03 00:00:00'));
        $experience6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience6->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience6->setLabel('dev-coffreo');
        try {
            $experience6->setCompany($this->getReference('company_14', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience6->addSkill($this->getReference('skill_67', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_69', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_72', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_78', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_81', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_85', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_87', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_90', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_91', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_92', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_95', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_98', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_100', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_101', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience6->addSkill($this->getReference('skill_102', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience6);
        $this->addReference('experience_17', $experience6);

        $experience7 = new Experience();
        $experience7->setTitle('Product Owner');
        $experience7->setSubtitle('Animation des sprints et coordination des développements');
        $experience7->setSummary('Au coeur de la transformation digitale RH, Coffreo a créé une plateforme SaaS pour accélérer et fluidifier les échanges entre les agences d’intérim, les prestataires de l’événementiel, les traiteurs, les sociétés de sécurité, les organismes médicaux, … et leurs salariés temporaires.');
        $experience7->setDescription('
<p class="text-justify pt5">Charnière entre l’équipe technique, l’équipe marketing et les clients.</p>
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Gestion de projet méthodologie Agile / Scrum</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Création et gestion du Product Backlog</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Rédaction des Users Stories</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Priorisation des besoins et exigences métier</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Suivi de l’avancement des projets, du planning de livraison et des engagements</span></li>
</ul>');
        $experience7->setStartDate(new \DateTime('2019-11-04 00:00:00'));
        $experience7->setEndDate(new \DateTime('2022-07-17 00:00:00'));
        $experience7->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience7->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience7->setLabel('po-coffreo');
        try {
            $experience7->setCompany($this->getReference('company_14', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience7->addSkill($this->getReference('skill_56', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_57', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_58', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_60', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_62', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_63', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_71', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_98', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_100', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_101', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience7->addSkill($this->getReference('skill_102', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience7);
        $this->addReference('experience_18', $experience7);

        $experience8 = new Experience();
        $experience8->setTitle('Product Owner');
        $experience8->setSubtitle('Encadrement R&D et qualité produit');
        $experience8->setSummary('Perfect Memory est un fournisseur d\'IA sémantique, ces technologies qui permettent de donner du sens et de la valeur à toutes les données générées par une organisation.');
        $experience8->setDescription('
<p class="text-justify pt5">Charnière entre les équipes de développement, l\'équipe design et l\'équipe qualité.</p>
<ul class="missions">
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Encadrement des équipes de développement</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>Qualité Produit</span></li>
    <li><i class="fas fa-tasks" aria-hidden="true"></i> <span>	
Rédaction des expressions de besoin et spécifications fonctionnelles</span></li>
</ul>');
        $experience8->setStartDate(new \DateTime('2022-07-18 00:00:00'));
        $experience8->setEndDate(new \DateTime('2024-04-22 00:00:00'));
        $experience8->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience8->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience8->setLabel('po-perfect');
        try {
            $experience8->setCompany($this->getReference('company_15', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience8->addSkill($this->getReference('skill_56', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience8->addSkill($this->getReference('skill_57', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience8->addSkill($this->getReference('skill_59', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience8->addSkill($this->getReference('skill_60', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience8->addSkill($this->getReference('skill_103', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience8);
        $this->addReference('experience_19', $experience8);

        $experience9 = new Experience();
        $experience9->setTitle('Technical Product Owner');
        $experience9->setSubtitle('Encadrement équipe Drive');
        $experience9->setSummary('Leviia est une entreprise française dont l\'ensemble de l\'infrastructure est hébergé exclusivement en France. Le code source est 100 % open source, sans aucune dépendance technologique américaine ou chinoise');
        $experience9->setDescription('<p class="pb-4">Coordination de l\'équipe technique.</p>
<ul class="missions">
<li><i class="fas fa-tasks"></i> <span>Développements basés sur la solution Nextcloud. Equipe de 4 développeurs et 5 administrateurs système. Suivi de déploiements clients. </span></li>
    <li><i class="fas fa-tasks"></i> <span>Documentation technique et utilisateur à destination externe.</span></li>
    <li><i class="fas fa-tasks"></i> <span>Mise en place d’un outil de data visualisation : Superset.</span></li>
    <li><i class="fas fa-tasks"></i> <span>Respect des exigences relatives à la norme ISO-27001 et HDS.</span></li>
</ul>');
        $experience9->setStartDate(new \DateTime('2024-08-21 00:00:00'));
        $experience9->setEndDate(null);
        $experience9->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience9->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $experience9->setLabel('po-leviia');
        try {
            $experience9->setCompany($this->getReference('company_16', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experience9->addSkill($this->getReference('skill_70', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience9->addSkill($this->getReference('skill_101', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience9->addSkill($this->getReference('skill_106', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience9->addSkill($this->getReference('skill_107', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        try {
            $experience9->addSkill($this->getReference('skill_108', Skill::class));
        } catch (\OutOfBoundsException $e) {
            // Reference target does not exist yet
        }
        $manager->persist($experience9);
        $this->addReference('experience_20', $experience9);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            CompanyAutoFixture::class,
            SkillAutoFixture::class,
        ];
    }
}
