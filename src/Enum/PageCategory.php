<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Enum;

enum PageCategory: string
{
    case CAREER = 'career';
    case INTEREST = 'interest';

    public function getLabel(): string
    {
        return match ($this) {
            self::CAREER => 'Parcours',
            self::INTEREST => 'Centres d\'intérêt',
        };
    }
}
