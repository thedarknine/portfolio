<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Enum\PageCategory;
use App\Repository\PageInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'page')]
#[ORM\Entity(repositoryClass: PageInfoRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class PageInfo
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SlugableTrait;
    use SortableTrait;

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
}
