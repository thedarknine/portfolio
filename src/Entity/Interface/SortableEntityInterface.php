<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity\Interface;

interface SortableEntityInterface
{
    public function getPosition(): ?int;

    public function setPosition(int $position): self;
}
