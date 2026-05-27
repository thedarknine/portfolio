<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait LocalizableTrait
{
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'City is required.')]
    #[Assert\Length(max: 100, maxMessage: 'City cannot exceed {{ limit }} characters.')]
    protected ?string $city = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Department is required.')]
    #[Assert\Positive(message: 'Department must be a positive number.')]
    #[Assert\Range(min: 1, max: 976, notInRangeMessage: 'Department must be between {{ min }} and {{ max }}.')]
    protected ?int $department = null;

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
}
