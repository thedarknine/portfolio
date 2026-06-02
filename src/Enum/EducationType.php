<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Enum;

enum EducationType: string
{
    case UNIVERSITARY = 'universitaire';
    case PROFESSIONAL = 'professionnel';

    /**
     * Optionnel : Un libellé propre pour l'affichage dans EasyAdmin.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::UNIVERSITARY => 'Universitaire',
            self::PROFESSIONAL => 'Professionnel',
        };
    }
}
