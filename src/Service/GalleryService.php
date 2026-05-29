<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Repository\ArcadeTypeRepository;
use App\Repository\CreationTypeRepository;
use App\Repository\PhotoTypeRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;

class GalleryService
{
    public function __construct(
        private ArcadeTypeRepository $arcadeTypeRepository,
        private CreationTypeRepository $creationTypeRepository,
        private PhotoTypeRepository $photoTypeRepository,
        #[Autowire(param: 'app.images_dir')] private string $imagesDir,
    ) {
    }

    /**
     * @return array{
     *  types: array<int, \App\Entity\ArcadeType>,
     *  items: array<string, array<int, string>>
     * }
     */
    public function getArcadeGallery(): array
    {
        $arcadeTypesList = $this->arcadeTypeRepository->getArcadeTypes();
        $arcadesList     = [];
        $imagesDir       = $this->imagesDir . '/arcade/';

        foreach ($arcadeTypesList as $type) {
            $slug               = $type->getSlug();
            $arcadesList[$slug] = [];
            $targetDir          = $imagesDir . $slug;

            if (!is_dir($targetDir)) {
                continue;
            }

            $finder = new Finder();
            $finder->files()->in($targetDir);

            foreach ($finder as $file) {
                $arcadesList[$slug][] = $file->getFileName();
            }

            shuffle($arcadesList[$slug]);
        }

        return [
            'types' => $arcadeTypesList,
            'items' => $arcadesList,
        ];
    }

    /**
     * @return array{
     *  types: array<int, \App\Entity\CreationType>,
     *  creations: array<string, array<int, string>>
     * }
     */
    public function getCreationsGallery(): array
    {
        $creationTypesList = $this->creationTypeRepository->getCreationTypes();
        $creationsList     = [];
        $imagesDir         = $this->imagesDir . '/creations/';

        foreach ($creationTypesList as $type) {
            $slug                 = $type->getSlug();
            $creationsList[$slug] = [];
            $targetDir            = $imagesDir . $slug;

            if (!is_dir($targetDir)) {
                continue;
            }

            $finder = new Finder();
            $finder->files()->in($targetDir);

            foreach ($finder as $file) {
                $creationsList[$slug][] = $file->getFileName();
            }

            shuffle($creationsList[$slug]);
        }

        return [
            'types'     => $creationTypesList,
            'creations' => $creationsList,
        ];
    }

    /**
     * @return array{
     *  types: array<int, \App\Entity\PhotoType>,
     *  photos: array<string, list<array{filename: string, caption: string}>>
     * }
     */
    public function getPhotosGallery(): array
    {
        $photoTypesList = $this->photoTypeRepository->getPhotoTypes();
        $photosList     = [];
        $imagesDir      = $this->imagesDir . '/photos/';

        foreach ($photoTypesList as $type) {
            $slug              = $type->getSlug();
            $photosList[$slug] = [];
            $targetDir         = $imagesDir . $slug;

            if (!is_dir($targetDir)) {
                continue;
            }

            $finder = new Finder();
            $finder->files()->in($targetDir);

            foreach ($finder as $file) {
                $filename = $file->getFileName();
                $caption  = explode('-', str_replace(['.JPG', '.jpg'], '', $filename));
                $title    = isset($caption[1]) ? str_replace('_', ' ', $caption[1]) : '';

                $photosList[$slug][] = [
                    'filename' => $filename,
                    'caption'  => $title,
                ];
            }

            shuffle($photosList[$slug]);
        }

        return [
            'types'  => $photoTypesList,
            'photos' => $photosList,
        ];
    }
}
