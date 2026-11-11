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
 * Project: optional full-width card.
 */
final class Version20261111191000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Project: add full_width';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD full_width TINYINT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP full_width');
    }
}
