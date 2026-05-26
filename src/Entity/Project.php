<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\LogoableTrait;
use App\Entity\Traits\NameableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'project')]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Project
{
    use TimeStampableTrait;
    use NameableTrait;
    use SlugableTrait;
    use LogoableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Period is required.')]
    #[Assert\Length(max: 100, maxMessage: 'Period cannot exceed {{ limit }} characters.')]
    private ?string $period = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Year is required.')]
    #[Assert\Range(min: 1990, max: 2099, notInRangeMessage: 'Year must be between {{ min }} and {{ max }}.')]
    private ?int $year = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Description is required.')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Screenshots path cannot exceed {{ limit }} characters.')]
    private ?string $screenshots = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Category is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Category cannot exceed {{ limit }} characters.')]
    private ?string $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Tags cannot exceed {{ limit }} characters.')]
    private ?string $tags = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getScreenshots(): ?string
    {
        return $this->screenshots;
    }

    public function setScreenshots(?string $screenshots): static
    {
        $this->screenshots = $screenshots;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

        return $this;
    }
}
