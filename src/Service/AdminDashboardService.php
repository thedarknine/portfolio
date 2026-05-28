<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Kernel;
use App\Repository\ExperienceRepository;
use App\Repository\PageInfoRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use Composer\InstalledVersions;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class AdminDashboardService
{
    public const CAREER_START_DATE = '2006-10-01';

    // Injected repositories directly
    public function __construct(
        private ParameterBagInterface $params,
        private Kernel $kernel,
        private ProjectRepository $projectRepository,
        private PageInfoRepository $pageInfoRepository,
        private SkillRepository $skillRepository,
        private ExperienceRepository $experienceRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        return [
            // Content
            'projects' => $this->projectRepository->count([]),
            'publicPages' => $this->pageInfoRepository->count([]),
            'draftProjects' => $this->projectRepository->count(['published' => false]),
            'publishedProjects' => $this->projectRepository->count(['published' => true]),

            // Last content
            'latestProject' => $this->projectRepository->findOneBy([], ['year' => 'DESC']),
            'latestPhoto' => $this->getLatestPhoto(),

            // Skills / stack
            'technologiesCount' => $this->skillRepository->count([]),

            // Ratios
            'projectsExperienceRatio' => $this->getProjectsExperienceRatio(),

            // Assets
            'totalImages' => $this->countImages(),
            'portfolioDiskUsage' => $this->getDirectorySize(),

            // Docker & Divers
            'phpVersion' => phpversion(),
            'symfonyVersion' => $this->kernel::VERSION,
            'easyAdminVersion' => InstalledVersions::getVersion('easycorp/easyadmin-bundle'),
            'yearsExperience' => $this->getNbYearsExperience(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getLatestPhoto(): ?array
    {
        $finder = new Finder();
        $directory = $this->params->get('kernel.project_dir').'/public/images/photos';

        if (!is_dir($directory)) {
            return null;
        }

        $finder->files()->in($directory)->sortByModifiedTime();
        $files = array_filter(iterator_to_array($finder), fn ($file) => 'hero-photos.jpg' !== $file->getFilename());

        if (empty($files)) {
            return null;
        }

        $latest = end($files);

        return [
            'filename' => $latest->getFilename(),
            'updatedAt' => date('d/m/Y H:i', $latest->getMTime()),
        ];
    }

    private function getProjectsExperienceRatio(): string
    {
        $projects = $this->projectRepository->count([]);
        $experiences = $this->experienceRepository->count([]);

        if (0 === $experiences) {
            return '0';
        }

        return number_format($projects / $experiences, 1);
    }

    private function countImages(): int
    {
        $finder = new Finder();
        $directory = $this->params->get('kernel.project_dir').'/public/images';

        if (!is_dir($directory)) {
            return 0;
        }

        $finder->files()->in($directory)->name(['*.jpg', '*.jpeg', '*.png', '*.webp']);

        return $finder->count();
    }

    private function getDirectorySize(): string
    {
        $directory = $this->params->get('kernel.project_dir').'/public/images';

        if (!is_dir($directory)) {
            return '0 B';
        }

        $finder = new Finder();
        // Ask Finder to list all files in the directory recursively
        $finder->files()->in($directory);

        $size = 0;
        foreach ($finder as $file) {
            $size += $file->getSize();
        }

        return $this->formatBytes($size);
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; ++$i) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    private function getNbYearsExperience(): int
    {
        $start = new \DateTime(self::CAREER_START_DATE);

        return $start->diff(new \DateTime())->y;
    }
}
