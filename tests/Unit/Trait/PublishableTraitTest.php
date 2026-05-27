<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Traits;

use App\Entity\Traits\PublishableTrait;
use PHPUnit\Framework\TestCase;

class PublishableTraitTest extends TestCase
{
    /**
     * Crée une instance d'une classe anonyme qui utilise le Trait.
     */
    private function createMockObject(): object
    {
        return new class {
            use PublishableTrait;
        };
    }

    /**
     * 🎯 Test de l'état initial par défaut.
     */
    public function testDefaultValueIsFalse(): void
    {
        $object = $this->createMockObject();

        $this->assertFalse(
            $object->isPublished(),
            'Par défaut, l\'état de publication doit être à "false".'
        );
    }

    /**
     * 🎯 Test du setter et du fluide interface (return $this).
     */
    public function testSetPublishedChangesStateAndReturnsSelf(): void
    {
        $object = $this->createMockObject();

        // On passe à true et on vérifie que le setter retourne bien l'instance ($this)
        $result = $object->setPublished(true);

        $this->assertSame($object, $result, 'Le setter doit retourner l\'instance de l\'objet.');
        $this->assertTrue($object->isPublished(), 'L\'état de publication aurait dû passer à "true".');

        // On rebascule à false pour valider le changement inverse
        $object->setPublished(false);
        $this->assertFalse($object->isPublished(), 'L\'état de publication aurait dû repasser à "false".');
    }
}
