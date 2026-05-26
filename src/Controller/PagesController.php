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
use App\Entity\PageInfo;
use App\Entity\PhotoType;
use App\Entity\Project;
use App\Entity\ResourceLink;
use App\Entity\Skill;
use App\Entity\SkillType;
use Carbon\Carbon;
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

    private function retrievePages(): array
    {
        return $this->doctrine->getRepository(PageInfo::class)->findAllAsArray([]);
    }

    private function retrieveResourceLinks(): array
    {
        return $this->doctrine->getRepository(ResourceLink::class)->findAllAsArray([]);
    }

    private function getNbYearsExperience(): int
    {
        $startWorking = new Carbon('2006-10-01');

        return (int) $startWorking->diff(Carbon::now())->y;
    }

    private function getImagesDir(): string
    {
        return $this->getParameter('kernel.project_dir').'/public/images/';
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $data['nbYearsExperience'] = $this->getNbYearsExperience();
        $data['pagesList'] = $this->retrievePages();
        $data['resourceLinks'] = $this->retrieveResourceLinks();
        $data['current'] = [
            'title' => 'Accueil',
            'slug' => 'home',
            'technicalName' => 'home',
        ];
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

    #[Route('/{slug}', name: 'app_page', requirements: ['slug' => '^(?!admin|login|logout).*$'])]
    public function page(string $slug): Response
    {
        $data['nbYearsExperience'] = $this->getNbYearsExperience();
        $data['pagesList'] = $this->retrievePages();
        $data['resourceLinks'] = $this->retrieveResourceLinks();
        $data['current'] = array_first(array_filter($data['pagesList'], function ($page) use ($slug) {
            return $page['slug'] === $slug;
        }));

        switch ($slug) {
            case 'experience':
                $data['experiencesList'] = $this->doctrine->getRepository(Experience::class)->getExperiencesWithCompany(20);
                break;
            case 'competences':
                $data['skillTypesList'] = $this->doctrine->getRepository(SkillType::class)->getSkillTypes();
                $data['skillsList'] = $this->doctrine->getRepository(Skill::class)->getSkillsOrderByType();
                break;
            case 'formation':
                $data['educationsList'] = $this->doctrine->getRepository(Education::class)->getEducationsWithSchool();
                break;
            case 'projets':
                $data['projectsList'] = $this->doctrine->getRepository(Project::class)->getProjects();
                break;
            case 'arcade':
                $arcadeTypesList = $this->doctrine->getRepository(ArcadeType::class)->getArcadeTypes();
                $arcadeList = [];
                foreach ($arcadeTypesList as $type) {
                    $arcadeList[$type->getSlug()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'arcade/'.$type->getSlug());
                    foreach ($finder as $file) {
                        $arcadeList[$type->getSlug()][] = $file->getFileName();
                    }
                }
                $data['arcadeTypesList'] = $arcadeTypesList;
                $data['arcadeList'] = $arcadeList;
                break;
            case 'creations':
                $creationTypesList = $this->doctrine->getRepository(CreationType::class)->getCreationTypes();
                $creationsList = [];
                foreach ($creationTypesList as $type) {
                    $creationsList[$type->getSlug()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'creations/'.$type->getSlug());
                    foreach ($finder as $file) {
                        $creationsList[$type->getSlug()][] = $file->getFileName();
                    }
                    shuffle($creationsList[$type->getSlug()]);
                }
                $data['creationTypesList'] = $creationTypesList;
                $data['creationsList'] = $creationsList;
                break;
            case 'photos':
                $photoTypesList = $this->doctrine->getRepository(PhotoType::class)->getPhotoTypes();
                $photosList = [];
                foreach ($photoTypesList as $type) {
                    $photosList[$type->getSlug()] = [];
                    $finder = new Finder();
                    $finder->in($this->getImagesDir().'photos/'.$type->getSlug());
                    foreach ($finder as $file) {
                        $caption = explode('-', str_replace(['.JPG', '.jpg'], '', $file->getFileName()));
                        $title = '';
                        if (array_key_exists(1, $caption)) {
                            $title = str_replace('_', ' ', $caption[1]);
                        }
                        $photosList[$type->getSlug()][] = [
                            'filename' => $file->getFileName(),
                            'caption' => $title,
                        ];
                    }
                    shuffle($photosList[$type->getSlug()]);
                }
                $data['photoTypesList'] = $photoTypesList;
                $data['photosList'] = $photosList;
                break;

            default:
                throw $this->createNotFoundException('Cette page n\'existe pas.');
        }

        return $this->render("pages/{$data['current']['technicalName']}.html.twig", [
            'data' => $data,
        ]);
    }
}
