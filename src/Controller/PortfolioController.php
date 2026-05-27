<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Repository\SkillTypeRepository;
use App\Service\GalleryService;
use App\Service\PageService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PortfolioController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine, private PageService $pageService)
    {
    }

    #[Route('/experience', name: 'app_page_experience')]
    public function experience(ExperienceRepository $experienceRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('experience');

        return $this->render('pages/experience.html.twig', [
            'current_page' => $page,
            'experiences_list' => $experienceRepository->getExperiencesWithCompany(20),
        ]);
    }

    #[Route('/competences', name: 'app_page_competences')]
    public function competences(SkillRepository $skillRepository, SkillTypeRepository $skillTypeRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('competences');

        return $this->render('pages/skills.html.twig', [
            'current_page' => $page,
            'skill_types_list' => $skillTypeRepository->getSkillTypes(),
            'skills_list' => $skillRepository->getSkillsOrderByType(),
        ]);
    }

    #[Route('/formation', name: 'app_page_formation')]
    public function formation(EducationRepository $educationRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('formation');

        return $this->render('pages/education.html.twig', [
            'current_page' => $page,
            'educations_universitary_list' => $educationRepository->getEducationsWithSchool('universitaire'),
            'educations_professional_list' => $educationRepository->getEducationsWithSchool('professionnel'),
        ]);
    }

    #[Route('/projets', name: 'app_page_projects')]
    public function projets(ProjectRepository $projectRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('projets');

        return $this->render('pages/projects.html.twig', [
            'current_page' => $page,
            'projects_list' => $projectRepository->findAll(['published' => true], ['year' => 'DESC']),
        ]);
    }

    #[Route('/arcade', name: 'app_page_arcade')]
    public function arcade(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('arcade');

        return $this->render('pages/arcade.html.twig', [
            'current_page' => $page,
            'arcade_data' => $galleryService->getArcadeGallery(),
        ]);
    }

    #[Route('/creations', name: 'app_page_creations')]
    public function creations(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('creations');

        return $this->render('pages/creations.html.twig', [
            'current_page' => $page,
            'creations_data' => $galleryService->getCreationsGallery(),
        ]);
    }

    #[Route('/photos', name: 'app_page_photos')]
    public function photos(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('photos');

        return $this->render('pages/photos.html.twig', [
            'current_page' => $page,
            'photos_data' => $galleryService->getPhotosGallery(),
        ]);
    }
}
