<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Traits\IconableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Repository\ResourceLinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'resource')]
#[ORM\Entity(repositoryClass: ResourceLinkRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class ResourceLink
{
    use TimeStampableTrait;
    use IconableTrait;
    use TitleableTrait;
    use SlugableTrait;
    use SortableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?bool $inHero = null;

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

    public function isInHero(): ?bool
    {
        return $this->inHero;
    }

    public function setInHero(bool $inHero): static
    {
        $this->inHero = $inHero;

        return $this;
    }
}
