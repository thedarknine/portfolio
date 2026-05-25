<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Trait\LabelTrait;
use App\Entity\Trait\NameTrait;
use App\Entity\Trait\PositionTrait;
use App\Entity\Trait\TimeStampableTrait;
use App\Repository\PhotoTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'photo_type')]
#[ORM\Entity(repositoryClass: PhotoTypeRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class PhotoType
{
    use TimeStampableTrait;
    use NameTrait;
    use LabelTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
