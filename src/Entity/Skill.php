<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Interface\SortableEntityInterface;
use App\Entity\Traits\LogoableTrait;
use App\Entity\Traits\NameableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Table(name: 'skill')]
#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Skill implements SortableEntityInterface
{
    use TimeStampableTrait;
    use NameableTrait;
    use SlugableTrait;
    use LogoableTrait;
    use SortableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Start year is required.')]
    #[Assert\Range(min: 1990, max: 2099, notInRangeMessage: 'Start year must be between {{ min }} and {{ max }}.')]
    private ?int $startYear = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1990, max: 2099, notInRangeMessage: 'End year must be between {{ min }} and {{ max }}.')]
    private ?int $endYear = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Level must be between {{ min }} and {{ max }}.')]
    private ?int $level = null;

    #[ORM\Column]
    private bool $display = false;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Skill type is required.')]
    #[Assert\Valid]
    private ?SkillType $skillType = null;

    /** @var Collection<int, Experience> */
    #[ORM\ManyToMany(targetEntity: Experience::class, mappedBy: 'skills')]
    #[Assert\Valid]
    private Collection $experiences;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): static
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getSkillType(): ?SkillType
    {
        return $this->skillType;
    }

    public function setSkillType(?SkillType $skillType): static
    {
        $this->skillType = $skillType;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->addSkill($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            $experience->removeSkill($this);
        }

        return $this;
    }

    public function isDisplay(): bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): static
    {
        $this->display = $display;

        return $this;
    }

    public function getDuration(): ?string
    {
        if (null === $this->endYear) {
            $endYear = intval((new \DateTime())->format('Y'));
        } else {
            $endYear = $this->endYear;
        }
        $duration = $endYear - $this->startYear;

        if ($duration <= 1) {
            return strval($duration).' an';
        }

        return strval($duration).' ans';
    }

    /**
     * Cross-validation: endYear cannot be before startYear.
     */
    #[Assert\Callback]
    public function validateYears(ExecutionContextInterface $context, mixed $payload): void
    {
        if (null !== $this->startYear && null !== $this->endYear && $this->endYear < $this->startYear) {
            $context->buildViolation('The end date cannot be before the start date.')
                ->atPath('endYear')
                ->addViolation();
        }
    }
}
