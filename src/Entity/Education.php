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
use App\Enum\EducationType;
use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
}
