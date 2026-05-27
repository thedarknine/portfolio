<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260525102101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD position INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB62072263045 ON page (technical_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB9FCB9B989D9B62 ON skill_type (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_140AB62072263045 ON page');
        $this->addSql('ALTER TABLE page DROP position');
        $this->addSql('DROP INDEX UNIQ_CB9FCB9B989D9B62 ON skill_type');
    }
}
