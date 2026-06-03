<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PortfolioController extends AbstractController
{
    public function __construct(private PageService $pageService)
    {
    }

    #[Route('/experience', name: 'app_experience', options: ['sitemap' => true])]
    public function experience(ExperienceRepository $experienceRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('experience');

        return $this->render('pages/experience.html.twig', [
            'current_page'     => $page,
            'experiences_list' => $experienceRepository->getExperiencesWithCompany(20),
        ]);
    }

    #[Route('/competences', name: 'app_skills', options: ['sitemap' => true])]
    public function skills(SkillRepository $skillRepository, SkillTypeRepository $skillTypeRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('competences');

        return $this->render('pages/skills.html.twig', [
            'current_page'     => $page,
            'skill_types_list' => $skillTypeRepository->getSkillTypes(),
            'skills_list'      => $skillRepository->getSkillsOrderByType(),
        ]);
    }

    #[Route('/formation', name: 'app_education', options: ['sitemap' => true])]
    public function education(EducationRepository $educationRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('formation');

        return $this->render('pages/education.html.twig', [
            'current_page'                 => $page,
            'educations_universitary_list' => $educationRepository->getEducationsWithSchool('universitaire'),
            'educations_professional_list' => $educationRepository->getEducationsWithSchool('professionnel'),
        ]);
    }

    #[Route('/projets', name: 'app_projects', options: ['sitemap' => true])]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $page = $this->pageService->getActivePageBySlug('projets');

        return $this->render('pages/projects.html.twig', [
            'current_page'  => $page,
            'projects_list' => $projectRepository->findBy(['published' => true], ['year' => 'DESC']),
        ]);
    }

    #[Route('/arcade', name: 'app_arcade', options: ['sitemap' => true])]
    public function arcade(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('arcade');

        return $this->render('pages/arcade.html.twig', [
            'current_page' => $page,
            'arcade_data'  => $galleryService->getArcadeGallery(),
        ]);
    }

    #[Route('/creations', name: 'app_creations', options: ['sitemap' => true])]
    public function creations(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('creations');

        return $this->render('pages/creations.html.twig', [
            'current_page'   => $page,
            'creations_data' => $galleryService->getCreationsGallery(),
        ]);
    }

    #[Route('/photos', name: 'app_photos', options: ['sitemap' => true])]
    public function photos(GalleryService $galleryService): Response
    {
        $page = $this->pageService->getActivePageBySlug('photos');

        return $this->render('pages/photos.html.twig', [
            'current_page' => $page,
            'photos_data'  => $galleryService->getPhotosGallery(),
        ]);
    }
}
