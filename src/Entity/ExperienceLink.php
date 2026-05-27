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
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Enum\LinkType;
use App\Repository\ExperienceLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExperienceLinkRepository::class)]
class ExperienceLink
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SlugableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'URL is required.')]
    #[Assert\Url(message: 'URL format is invalid.')]
    #[Assert\Length(max: 255, maxMessage: 'URL cannot exceed {{ limit }} characters.')]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Experience is required.')]
    #[Assert\Valid]
    private ?Experience $experience = null;

    #[ORM\Column(enumType: LinkType::class)]
    #[Assert\NotNull(message: 'Link type is required.')]
    #[Assert\Valid]
    private ?LinkType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getType(): ?LinkType
    {
        return $this->type;
    }

    public function setType(LinkType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
