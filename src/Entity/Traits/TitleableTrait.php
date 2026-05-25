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

trait TitleableTrait
{
    #[ORM\Column(length: 120)]
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
