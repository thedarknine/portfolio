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
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260704214648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE screenshot (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, position INT NOT NULL, project_id INT NOT NULL, INDEX IDX_58991E41166D1F9C (project_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE screenshot ADD CONSTRAINT FK_58991E41166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project DROP screenshots');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE screenshot DROP FOREIGN KEY FK_58991E41166D1F9C');
        $this->addSql('DROP TABLE screenshot');
        $this->addSql('ALTER TABLE project ADD screenshots VARCHAR(255) DEFAULT NULL');
    }
}
