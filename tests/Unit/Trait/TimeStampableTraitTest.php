<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\TimeStampableTrait;
use PHPUnit\Framework\TestCase;

class TimeStampableTraitTest extends TestCase
{
    public function testTimestampableGettersAndSetters(): void
    {
        // Create an anonymous class on the fly which uses the Trait
        $dummyEntity = new class {
            use TimeStampableTrait;
        };

        $now = new \DateTimeImmutable();

        // Test the behavior of the Trait methods
        $dummyEntity->setCreatedAt($now);
        $this->assertSame($now, $dummyEntity->getCreatedAt());
        $dummyEntity->setUpdatedAt($now);
        $this->assertSame($now, $dummyEntity->getUpdatedAt());
    }

    /**
     * Test that both dates are set when the entity is persisted.
     */
    public function testPrePersistSetsBothDates(): void
    {
        $dummyEntity = new class {
            use TimeStampableTrait;
        };

        // Before the PrePersist, updatedAt must be null
        $this->assertNull($dummyEntity->getUpdatedAt());

        // Simulate the triggering of the Doctrine event
        $dummyEntity->setCreatedAtValue();

        // Verify that both dates have been initialized
        $this->assertInstanceOf(\DateTimeInterface::class, $dummyEntity->getCreatedAt());
        $this->assertInstanceOf(\DateTimeInterface::class, $dummyEntity->getUpdatedAt());

        // Verify that the dates correspond to "now" (within a few seconds)
        $this->assertEqualsWithDelta(time(), $dummyEntity->getCreatedAt()->getTimestamp(), 2);
        $this->assertEqualsWithDelta(time(), $dummyEntity->getUpdatedAt()->getTimestamp(), 2);
    }

    /**
     * Test that only the updated_at date is changed when the entity is updated.
     */
    public function testPreUpdateChangesOnlyUpdatedAt(): void
    {
        $dummyEntity = new class {
            use TimeStampableTrait;
        };

        // Simulate a distant past for the creation
        $pastDate = new \DateTime('2010-01-01 00:00:00');
        $dummyEntity->setCreatedAt($pastDate);
        $dummyEntity->setUpdatedAt($pastDate);

        // Simulate a pause of a fraction of a second (not necessary here as we verify the difference)
        // Trigger the Doctrine update event
        $dummyEntity->setUpdatedAtValue();

        // The creation date must not have changed
        $this->assertSame($pastDate, $dummyEntity->getCreatedAt());

        // The update date must be different from the past date and correspond to today
        $this->assertNotEquals($pastDate, $dummyEntity->getUpdatedAt());
        $this->assertEqualsWithDelta(time(), $dummyEntity->getUpdatedAt()->getTimestamp(), 2);
    }
}
