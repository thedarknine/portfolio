<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Experience items are now sorted per experience (Gedmo SortableGroup):
 * renumber their positions from 0 within each experience, keeping the current order.
 */
final class Version20261109002500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Experience items: positions renumbered per experience';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE experience_item ei
            JOIN (
                SELECT id, ROW_NUMBER() OVER (PARTITION BY experience_id ORDER BY position, id) - 1 AS new_position
                FROM experience_item
            ) ranked ON ranked.id = ei.id
            SET ei.position = ranked.new_position
            SQL);
    }

    public function down(Schema $schema): void
    {
        // Positions per experience remain valid: nothing to revert
    }
}
