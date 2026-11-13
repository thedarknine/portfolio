<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Enum;

enum PageCategory: string
{
    case CAREER   = 'career';
    case INTEREST = 'interest';
    case ABOUT    = 'about';

    public function getLabel(): string
    {
        return match ($this) {
            self::CAREER   => 'Parcours',
            self::INTEREST => 'Centres d\'intérêt',
            self::ABOUT    => 'À propos',
        };
    }
}
