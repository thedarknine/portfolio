<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\LabelTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\PhotoTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'photo_type')]
#[ORM\Entity(repositoryClass: PhotoTypeRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class PhotoType
{
    use TimeStampableTrait;
    use LabelTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column]
    #[Gedmo\SortablePosition]
    private ?int $position = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
