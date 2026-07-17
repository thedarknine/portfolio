<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Interface\SortableEntityInterface;
use App\Entity\Traits\PublishableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Enum\PageCategory;
use App\Repository\PageInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Table(name: 'page')]
#[ORM\Entity(repositoryClass: PageInfoRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class PageInfo implements SortableEntityInterface
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SlugableTrait;
    use SortableTrait;
    use PublishableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120, unique: true)]
    #[Assert\NotBlank(message: 'Technical name is required.')]
    #[Assert\Length(max: 120, maxMessage: 'Technical name cannot exceed {{ limit }} characters.')]
    private ?string $technicalName = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: 'Tagline is required.')]
    #[Assert\Length(max: 120, maxMessage: 'Tagline cannot exceed {{ limit }} characters.')]
    private ?string $tagline = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Subtitle is required.')]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Quote is required.')]
    private ?string $quote = null;

    #[ORM\Column]
    private ?bool $inHeader = null;

    #[ORM\Column(enumType: PageCategory::class)]
    #[Assert\NotNull(message: 'Page category is required.')]
    #[Assert\Valid]
    private ?PageCategory $category = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?self $parent = null;

    /** @var Collection<int, PageInfo> */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    #[ORM\OrderBy(['position' => 'ASC'])] // adapte le nom de colonne selon ton SortableTrait
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): static
    {
        $this->technicalName = $technicalName;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(string $tagline): static
    {
        $this->tagline = $tagline;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): static
    {
        $this->quote = $quote;

        return $this;
    }

    public function isInHeader(): ?bool
    {
        return $this->inHeader;
    }

    public function setInHeader(bool $inHeader): static
    {
        $this->inHeader = $inHeader;

        return $this;
    }

    public function getCategory(): ?PageCategory
    {
        return $this->category;
    }

    public function setCategory(PageCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, PageInfo>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return PageInfo[]
     */
    public function getPublishedChildren(): array
    {
        return $this->children
            ->filter(fn (self $child) => $child->isPublished())
            ->toArray();
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }

    #[Assert\Callback]
    public function validateDepth(ExecutionContextInterface $context): void
    {
        if ($this === $this->parent) {
            $context->buildViolation('Une page ne peut pas être son propre parent.')
                ->atPath('parent')
                ->addViolation();

            return;
        }

        if (null === $this->parent) {
            return;
        }

        if (null !== $this->parent->getParent()) {
            $context->buildViolation(
                'Impossible de choisir une sous-page comme parent (profondeur maximale : 1).',
            )->atPath('parent')->addViolation();
        }

        if (!$this->children->isEmpty()) {
            $context->buildViolation(
                'Une sous-page ne peut pas avoir elle-même des sous-pages (profondeur maximale : 1).',
            )->atPath('parent')->addViolation();
        }
    }
}
