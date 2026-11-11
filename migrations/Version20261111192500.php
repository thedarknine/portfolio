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
 * Screenshots are now sorted per project (Gedmo SortableGroup):
 * renumber their positions from 0 within each project, keeping the current order.
 */
final class Version20261111192500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Screenshots: positions renumbered per project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE screenshot s
            JOIN (
                SELECT id, ROW_NUMBER() OVER (PARTITION BY project_id ORDER BY position, id) - 1 AS new_position
                FROM screenshot
            ) ranked ON ranked.id = s.id
            SET s.position = ranked.new_position
            SQL);
    }

    public function down(Schema $schema): void
    {
        // Positions per project remain valid: nothing to revert
    }
}
