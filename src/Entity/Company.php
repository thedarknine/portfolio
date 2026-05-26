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
use App\Entity\Traits\LocalizableTrait;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'company')]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Company
{
    use TimeStampableTrait;
    use NameableTrait;
    use SlugableTrait;
    use LogoableTrait;
    use LocalizableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: 'URL format is invalid.')]
    #[Assert\Length(max: 255, maxMessage: 'URL cannot exceed {{ limit }} characters.')]
    private ?string $url = null;

    /** @var Collection<int, Experience> */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Experience::class)]
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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
            $experience->setCompany($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCompany() === $this) {
                $experience->setCompany(null);
            }
        }

        return $this;
    }
}
