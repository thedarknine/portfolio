<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Interface\SortableEntityInterface;
use App\Entity\Traits\NameableTrait;
use App\Entity\Traits\SlugableTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\CreationTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'creation_type')]
#[ORM\Entity(repositoryClass: CreationTypeRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class CreationType implements SortableEntityInterface
{
    use TimeStampableTrait;
    use NameableTrait;
    use SlugableTrait;
    use SortableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
