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
final class Version20260525095115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE arcade_type CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41D796C5989D9B62 ON arcade_type (slug)');
        $this->addSql('ALTER TABLE company CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F989D9B62 ON company (slug)');
        $this->addSql('ALTER TABLE creation_type CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51A8110D989D9B62 ON creation_type (slug)');
        $this->addSql('ALTER TABLE education CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB0A5ED2989D9B62 ON education (slug)');
        $this->addSql('ALTER TABLE experience CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_590C103989D9B62 ON experience (slug)');
        $this->addSql('ALTER TABLE experience_link CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62BC56EC989D9B62 ON experience_link (slug)');
        $this->addSql('ALTER TABLE page CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB620989D9B62 ON page (slug)');
        $this->addSql('ALTER TABLE photo_type CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DEFE5DD989D9B62 ON photo_type (slug)');
        $this->addSql('ALTER TABLE project CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');
        $this->addSql('ALTER TABLE school CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F99EDABB989D9B62 ON school (slug)');
        $this->addSql('ALTER TABLE skill CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E3DE477989D9B62 ON skill (slug)');
        $this->addSql('ALTER TABLE skill_type CHANGE label slug VARCHAR(120) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB9FCB9B989D9B62 ON skill_type (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_41D796C5989D9B62 ON arcade_type');
        $this->addSql('ALTER TABLE arcade_type CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4FBF094F989D9B62 ON company');
        $this->addSql('ALTER TABLE company CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_51A8110D989D9B62 ON creation_type');
        $this->addSql('ALTER TABLE creation_type CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_DB0A5ED2989D9B62 ON education');
        $this->addSql('ALTER TABLE education CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_590C103989D9B62 ON experience');
        $this->addSql('ALTER TABLE experience CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_62BC56EC989D9B62 ON experience_link');
        $this->addSql('ALTER TABLE experience_link CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_140AB620989D9B62 ON page');
        $this->addSql('ALTER TABLE page CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_DEFE5DD989D9B62 ON photo_type');
        $this->addSql('ALTER TABLE photo_type CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE989D9B62 ON project');
        $this->addSql('ALTER TABLE project CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_F99EDABB989D9B62 ON school');
        $this->addSql('ALTER TABLE school CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_5E3DE477989D9B62 ON skill');
        $this->addSql('ALTER TABLE skill CHANGE slug label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_CB9FCB9B989D9B62 ON skill_type');
        $this->addSql('ALTER TABLE skill_type CHANGE slug label VARCHAR(120) NOT NULL');
    }
}
