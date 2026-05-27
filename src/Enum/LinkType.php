<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Enum;

enum LinkType: string
{
    case DOCUMENT = 'document';
    case PDF = 'pdf';
    case EXTERNAL = 'external';

    /**
     * Return a user-friendly label for forms and display.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::DOCUMENT => 'Document',
            self::PDF => 'Fichier PDF',
            self::EXTERNAL => 'Lien externe',
        };
    }

    /**
     * Return the FontAwesome class for EasyAdmin (and the front).
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::DOCUMENT => 'fa-file-word',
            self::PDF => 'fa-file-pdf text-danger',
            self::EXTERNAL => 'fa-external-link-alt',
        };
    }
}
