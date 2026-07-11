<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Enum;

enum LinkType: string
{
    case DOCUMENT = 'document';
    case PDF      = 'pdf';
    case EXTERNAL = 'external';
    case INTERNAL = 'internal';
    case DETAIL   = 'detail';

    /**
     * Return a user-friendly label for forms and display.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::DOCUMENT => 'Document',
            self::PDF      => 'Fichier PDF',
            self::EXTERNAL => 'Lien externe',
            self::INTERNAL => 'Lien interne',
            self::DETAIL   => 'Détail',
        };
    }

    /**
     * Return the FontAwesome class for EasyAdmin (and the front).
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::DOCUMENT => 'fa7-solid:file-word',
            self::PDF      => 'flowbite:file-pdf-outline text-danger',
            self::EXTERNAL => 'fa7-solid:globe-europe',
            self::INTERNAL => 'fa7-solid:link',
            self::DETAIL   => 'fa7-solid:eye',
        };
    }
}
