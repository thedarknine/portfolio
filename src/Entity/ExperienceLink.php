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
use App\Enum\LinkType;
use App\Repository\ExperienceLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\AtLeastOneOf([
        new Assert\Url(
            protocols: ['http', 'https'],
            message: 'URL format is invalid.',
        ),
        new Assert\Regex(
            pattern: '~^/.*$~',
            message: 'The URI must start with "/".',
        ),
    ])]
    #[Assert\Length(max: 255, maxMessage: 'URL cannot exceed {{ limit }} characters.')]
    private ?string $url = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?PageInfo $page = null;

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

    // public function setUrl(?string $url): static
    // {
    //     $this->url = $url;

    //     return $this;
    // }
    public function setUrl(?string $url): static
    {
        $this->url = (null === $url || '' === trim($url)) ? null : $url;

        return $this;
    }

    public function getPage(): ?PageInfo
    {
        return $this->page;
    }

    public function setPage(?PageInfo $page): static
    {
        $this->page = $page;

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

    /**
     * Retourne l'URL finale à utiliser, que ce soit une page liée ou une URL saisie manuellement.
     */
    public function getResolvedUrl(): ?string
    {
        if (null !== $this->page) {
            $parent = $this->page->getParent();

            return null !== $parent
                ? '/' . $parent->getSlug() . '/' . $this->page->getSlug()
                : '/' . $this->page->getSlug();
        }

        return $this->url;
    }

    #[Assert\Callback]
    public function validateUrlOrPage(ExecutionContextInterface $context): void
    {
        $isDetailType = LinkType::DETAIL === $this->type;
        $hasUrl       = null !== $this->url && '' !== trim($this->url);

        if ($isDetailType) {
            if (null === $this->page) {
                $context->buildViolation('A page must be selected when link type is "Detail".')
                    ->atPath('page')
                    ->addViolation();
            }

            if ($hasUrl) {
                $context->buildViolation('URL must be empty when link type is "Detail" — select a page instead.')
                    ->atPath('url')
                    ->addViolation();
            }
        } else {
            if (!$hasUrl) {
                $context->buildViolation('URL is required for this link type.')
                    ->atPath('url')
                    ->addViolation();
            }

            if (null !== $this->page) {
                $context->buildViolation('Page must be empty for this link type — enter a URL instead.')
                    ->atPath('page')
                    ->addViolation();
            }
        }
    }
}
