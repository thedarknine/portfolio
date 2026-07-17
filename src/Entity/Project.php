<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\LogoableTrait;
use App\Entity\Traits\NameableTrait;
use App\Entity\Traits\PublishableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    use PublishableTrait;

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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Category is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Category cannot exceed {{ limit }} characters.')]
    private ?string $category = null;

    /** @var Collection<int, ProjectTag> */
    #[ORM\ManyToMany(targetEntity: ProjectTag::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_project_tag')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $tags;

    /** @var Collection<int, Screenshot> */
    #[ORM\OneToMany(targetEntity: Screenshot::class, mappedBy: 'project', cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $screenshots;

    public function __construct()
    {
        $this->tags        = new ArrayCollection();
        $this->screenshots = new ArrayCollection();
    }

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ProjectTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(ProjectTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addProject($this);
        }

        return $this;
    }

    public function removeTag(ProjectTag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Screenshot>
     */
    public function getScreenshots(): Collection
    {
        return $this->screenshots;
    }

    public function addScreenshot(Screenshot $screenshot): static
    {
        if (!$this->screenshots->contains($screenshot)) {
            $this->screenshots->add($screenshot);
            $screenshot->setProject($this);
        }

        return $this;
    }

    public function removeScreenshot(Screenshot $screenshot): static
    {
        if ($this->screenshots->removeElement($screenshot)) {
            // set the owning side to null (unless already changed)
            if ($screenshot->getProject() === $this) {
                $screenshot->setProject(null);
            }
        }

        return $this;
    }
}
