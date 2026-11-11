<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Enum\EducationType;
use App\Repository\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Table(name: 'education')]
#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Education
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SlugableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Year is required.')]
    #[Assert\Range(min: 1990, max: 2099, notInRangeMessage: 'Year must be between {{ min }} and {{ max }}.')]
    private ?int $year = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Details are required.')]
    #[Assert\Length(max: 180, maxMessage: 'Details cannot exceed {{ limit }} characters.')]
    private ?string $details = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(max: 180, maxMessage: 'Speciality cannot exceed {{ limit }} characters.')]
    private ?string $speciality = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: 'Mention cannot exceed {{ limit }} characters.')]
    private ?string $mention = null;

    /** What the training brought, shown under the details on the education page */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $takeaway = null;

    #[ORM\Column(length: 120, nullable: true)]
    #[Assert\Length(max: 120, maxMessage: 'Link label cannot exceed {{ limit }} characters.')]
    private ?string $linkLabel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\AtLeastOneOf([
        new Assert\Url(protocols: ['http', 'https'], message: 'URL format is invalid.'),
        new Assert\Regex(pattern: '~^/.*$~', message: 'The URI must start with "/".'),
    ])]
    #[Assert\Length(max: 255, maxMessage: 'Link URL cannot exceed {{ limit }} characters.')]
    private ?string $linkUrl = null;

    #[ORM\ManyToOne(inversedBy: 'education')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'School is required.')]
    #[Assert\Valid]
    private ?School $school = null;

    #[ORM\Column(length: 30, type: 'string', enumType: EducationType::class)]
    #[Assert\NotNull(message: 'Education type is required.')]
    #[Assert\Valid]
    private ?EducationType $type = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): ?EducationType
    {
        return $this->type;
    }

    public function setType(?EducationType $type): self
    {
        $this->type = $type;

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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->mention;
    }

    public function setMention(?string $mention): static
    {
        $this->mention = $mention;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    public function getTakeaway(): ?string
    {
        return $this->takeaway;
    }

    public function setTakeaway(?string $takeaway): static
    {
        $this->takeaway = $takeaway;

        return $this;
    }

    public function getLinkLabel(): ?string
    {
        return $this->linkLabel;
    }

    public function setLinkLabel(?string $linkLabel): static
    {
        $this->linkLabel = $linkLabel;

        return $this;
    }

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(?string $linkUrl): static
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }

    /**
     * A link is shown only with both a label and a URL: reject one without the other.
     */
    #[Assert\Callback]
    public function validateLink(ExecutionContextInterface $context): void
    {
        if ((null === $this->linkLabel) !== (null === $this->linkUrl)) {
            $context->buildViolation('A link needs both a label and a URL.')
                ->atPath(null === $this->linkLabel ? 'linkLabel' : 'linkUrl')
                ->addViolation();
        }
    }
}
