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
use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'school')]
#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class School
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
    #[Assert\NotBlank(message: 'City is required.')]
    #[Assert\Length(max: 100, maxMessage: 'City cannot exceed {{ limit }} characters.')]
    private ?string $city = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Department is required.')]
    #[Assert\Range(min: 1, max: 976, notInRangeMessage: 'Le département doit être un numéro valide.')]
    private ?int $department = null;

    /** @var Collection<int, Education> */
    #[ORM\OneToMany(mappedBy: 'school', targetEntity: Education::class)]
    #[Assert\Valid]
    private Collection $education;

    public function __construct()
    {
        $this->education = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartment(): ?int
    {
        return $this->department;
    }

    public function setDepartment(int $department): static
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Education>
     */
    public function getEducation(): Collection
    {
        return $this->education;
    }

    public function addEducation(Education $education): static
    {
        if (!$this->education->contains($education)) {
            $this->education->add($education);
            $education->setSchool($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): static
    {
        if ($this->education->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getSchool() === $this) {
                $education->setSchool(null);
            }
        }

        return $this;
    }
}
