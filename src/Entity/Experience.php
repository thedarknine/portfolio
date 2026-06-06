<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Repository\ExperienceRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Table(name: 'experience')]
#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Experience
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SlugableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 120, nullable: true)]
    #[Assert\Length(max: 120, maxMessage: 'Subtitle cannot exceed {{ limit }} characters.')]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Start date is required.')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /** @var Collection<int, Skill> */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'experiences')]
    private Collection $skills;

    /** @var Collection<int, ExperienceItem> */
    #[ORM\OneToMany(targetEntity: ExperienceItem::class, mappedBy: 'experience')]
    #[Assert\Valid]
    private Collection $items;

    /** @var Collection<int, ExperienceLink> */
    #[ORM\OneToMany(targetEntity: ExperienceLink::class, mappedBy: 'experience')]
    #[Assert\Valid]
    private Collection $links;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->items  = new ArrayCollection();
        $this->links  = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return array<string, float|int<-11, 11>>
     */
    public function getDuration(): array
    {
        $startDate = new Carbon($this->startDate);
        $endDate   = $this->endDate ? Carbon::instance($this->endDate) : Carbon::now();
        $diff      = $startDate->diff($endDate);

        return ['nbYears' => (int) $diff->y, 'nbMonths' => (int) $diff->m];
    }

    /**
     * @return Collection<int, ExperienceItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ExperienceItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setExperience($this);
        }

        return $this;
    }

    public function removeItem(ExperienceItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getExperience() === $this) {
                $item->setExperience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExperienceLink>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(ExperienceLink $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setExperience($this);
        }

        return $this;
    }

    public function removeLink(ExperienceLink $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getExperience() === $this) {
                $link->setExperience(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->endDate && $this->startDate > $this->endDate) {
            $context->buildViolation('The end date cannot be before the start date.')
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
