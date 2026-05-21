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
        $pageName = $slug;

        switch ($slug) {
            case 'experience':
                $data['experiencesList'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(20);
                break;
            case 'competences':
                $pageName = 'skills';
                $data['skillTypesList'] = $this->doctrine->getRepository(SkillType::class)->getSkillTypes();
                $data['skillsList'] = $this->doctrine->getRepository(Skill::class)->getSkillsOrderByType();
                break;
            case 'formation':
                $pageName = 'education';
                $data['educationsList'] = $this->doctrine->getRepository(Education::class)->getEducationsWithSchool();
                break;
            case 'projets':
                $pageName = 'projects';
                $data['projectsList'] = $this->doctrine->getRepository(Project::class)->getProjects();
                break;
            case 'arcade':
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

            default:
                return $this->redirectToRoute('app_index');
        }

        return $this->render("pages/{$pageName}.html.twig", [
            'page' => $pageName,
            'data' => $data,
        ]);
    }
}
