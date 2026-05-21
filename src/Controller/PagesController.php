<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ArcadeType;
use App\Entity\CreationType;
use App\Entity\Education;
use App\Entity\Experience;
use App\Entity\PhotoType;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\SkillType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PagesController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    private function getNbYearsExperience(): int
    {
        $startDate = new \DateTime('2006-10-01');
        $currentDate = new \DateTime();

        return $startDate->diff($currentDate)->y;
    }

    private function getImagesDir(): string
    {
        return $this->getParameter('kernel.project_dir').'/public/images/';
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $data['nbYearsExperience'] = $this->getNbYearsExperience();
        $data['mentalLandscape'] = [
            'rugby' => ['image' => 'images/home/rugby-asm.jpeg', 'alt' => 'Rugby', 'big' => false],
            'zen' => ['image' => 'images/home/lac-zen.jpg', 'alt' => 'Sérénité', 'big' => true],
            'chat' => ['image' => 'images/home/chat.jpg', 'alt' => 'Chat', 'big' => false],
            'auvergne' => ['image' => 'images/home/auvergne.jpg', 'alt' => 'Auvergne', 'big' => false],
            'equipe' => ['image' => 'images/home/equipe.jpg', 'alt' => 'Equipe', 'big' => false],
        ];
        $data['lastExperiences'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(3);

        return $this->render('pages/home.html.twig', [
            'page' => 'home',
            'data' => $data,
        ]);
    }

    #[Route('/{slug}', name: 'app_page')]
    public function page(string $slug): Response
    {
        $data['nbYearsExperience'] = $this->getNbYearsExperience();
        $pagename = $slug;

        switch ($slug) {
            case 'experience':
                $pagetitle = 'Expérience';
                $tagline = 'Construire';
                $subtitle = 'Du code au produit : mon parcours en mouvement';
                $quote = "J’aime transformer des idées en réalisations concrètes, apprendre en faisant et faire évoluer ce que je construis.";
                $data['experiencesList'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(20);
                break;
            case 'competences':
                $pagename = 'skills';
                $tagline = 'Maîtriser';
                $subtitle = 'Des outils au service de solutions fiables et utiles';
                $quote = "Je cherche à concevoir des produits simples, utiles et durables, avec une approche pragmatique et humaine.";
                $data['skillTypesList'] = $this->doctrine->getRepository(SkillType::class)->getSkillTypes();
                $data['skillsList'] = $this->doctrine->getRepository(Skill::class)->getSkillsOrderByType();
                break;
            case 'formation':
                $pagename = 'education';
                $tagline = 'Apprendre';
                $subtitle = 'Les fondations techniques... et l\'évolution continue';
                $quote = "Pour moi, la veille et l'apprentissage ne s'arrêtent jamais. C'est un cycle permanent d'expérimentation.";
                $data['educationsList'] = $this->doctrine->getRepository(Education::class)->getEducationsWithSchool();
                break;
            case 'projets':
                $pagename = 'projects';
                $tagline = 'Expérimenter';
                $subtitle = 'Chaque projet est un terrain d’exploration';
                $quote = "Coder sans contraintes, tester de nouvelles technos et s'autoriser à chercher juste pour le plaisir de comprendre.";
                $data['projectsList'] = $this->doctrine->getRepository(Project::class)->getProjects();
                break;
            case 'arcade':
                $tagline = 'Assembler';
                $subtitle = 'Un défi technique entre nostalgie et pop culture';
                $quote = "Quand la passion des vieux jeux rencontre le plaisir de bidouiller le hardware.";
                $arcadeTypesList = $this->doctrine->getRepository(ArcadeType::class)->getArcadeTypes();
                $arcadeList = [];
                foreach ($arcadeTypesList as $type) {
                    $arcadeList[$type->getLabel()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'arcade/'.$type->getLabel());
                    foreach ($finder as $file) {
                        $arcadeList[$type->getLabel()][] = $file->getFileName();
                    }
                }
                $data['arcadeTypesList'] = $arcadeTypesList;
                $data['arcadeList'] = $arcadeList;
                break;
            case 'creations':
                $tagline = 'Façonner';
                $subtitle = 'Entre matière, patience et intuition';
                $quote = "Poser les écrans pour laisser parler la matière, la patience et l'imagination.";
                $creationTypesList = $this->doctrine->getRepository(CreationType::class)->getCreationTypes();
                $creationsList = [];
                foreach ($creationTypesList as $type) {
                    $creationsList[$type->getLabel()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'creations/'.$type->getLabel());
                    foreach ($finder as $file) {
                        $creationsList[$type->getLabel()][] = $file->getFileName();
                    }
                    shuffle($creationsList[$type->getLabel()]);
                }
                $data['creationTypesList'] = $creationTypesList;
                $data['creationsList'] = $creationsList;
                break;
            case 'photos':
                $tagline = 'Observer';
                $subtitle = 'Regards sur l\'Auvergne, les détails et les lumières';
                $quote = "Capturer l'instant, cadrer une atmosphère et prêter attention aux détails qui échappent au premier coup d'œil.";
                $photoTypesList = $this->doctrine->getRepository(PhotoType::class)->getPhotoTypes();
                $photosList = [];
                foreach ($photoTypesList as $type) {
                    $photosList[$type->getLabel()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'photos/'.$type->getLabel());
                    foreach ($finder as $file) {
                        $caption = explode('-', str_replace(['.JPG', '.jpg'], '', $file->getFileName()));
                        $title = '';
                        if (array_key_exists(1, $caption)) {
                            $title = str_replace('_', ' ', $caption[1]);
                        }
                        $photosList[$type->getLabel()][] = [
                            'filename' => $file->getFileName(),
                            'caption' => $title,
                        ];
                    }
                    shuffle($photosList[$type->getLabel()]);
                }
                $data['photoTypesList'] = $photoTypesList;
                $data['photosList'] = $photosList;
                break;

            default:
                return $this->redirectToRoute('app_index');
        }

        return $this->render("pages/{$pagename}.html.twig", [
            'page' => $pagename,
            'slug' => $slug,
            'pagetitle' => $pagetitle ?? null,
            'tagline' => $tagline ?? null,
            'subtitle' => $subtitle ?? null,
            'quote' => $quote ?? null,
            'data' => $data,
        ]);
    }
}
