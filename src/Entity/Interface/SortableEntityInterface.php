<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Entity\Interface;

interface SortableEntityInterface
{
    public function getPosition(): ?int;

    public function setPosition(int $position): self;
}
