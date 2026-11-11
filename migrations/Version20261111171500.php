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
 * Education: optional takeaway text and link.
 */
final class Version20261111171500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Education: add takeaway, link_label and link_url';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE education ADD takeaway LONGTEXT DEFAULT NULL, ADD link_label VARCHAR(120) DEFAULT NULL, ADD link_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE education DROP takeaway, DROP link_label, DROP link_url');
    }
}
