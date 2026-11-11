<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Skills are now sorted per skill type (Gedmo SortableGroup):
 * renumber their positions from 0 within each type, keeping the current order
 * (current position, then highest level first).
 */
final class Version20261109201500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Skills: positions renumbered per skill type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE skill s
            JOIN (
                SELECT id, ROW_NUMBER() OVER (PARTITION BY skill_type_id ORDER BY position, level DESC, id) - 1 AS new_position
                FROM skill
            ) ranked ON ranked.id = s.id
            SET s.position = ranked.new_position
            SQL);
    }

    public function down(Schema $schema): void
    {
        // Positions per skill type remain valid: nothing to revert
    }
}
