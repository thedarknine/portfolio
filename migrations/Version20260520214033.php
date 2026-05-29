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
final class Version20260520214033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arcade_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, position INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, city VARCHAR(100) NOT NULL, department INT NOT NULL, url VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, logo VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE creation_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, position INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(120) NOT NULL, year INT NOT NULL, details VARCHAR(180) NOT NULL, speciality VARCHAR(180) DEFAULT NULL, mention VARCHAR(100) DEFAULT NULL, type VARCHAR(10) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, school_id INT NOT NULL, INDEX IDX_DB0A5ED2C32A47EE (school_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(120) NOT NULL, subtitle VARCHAR(120) DEFAULT NULL, summary LONGTEXT DEFAULT NULL, description LONGTEXT NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, company_id INT NOT NULL, INDEX IDX_590C103979B1AD6 (company_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE experience_skill (experience_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_3D6F986146E90E27 (experience_id), INDEX IDX_3D6F98615585C142 (skill_id), PRIMARY KEY (experience_id, skill_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE experience_item (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, position INT NOT NULL, picto VARCHAR(255) NOT NULL, experience_id INT NOT NULL, INDEX IDX_4B0BEA0346E90E27 (experience_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE photo_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, position INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, period VARCHAR(100) NOT NULL, year INT NOT NULL, description LONGTEXT NOT NULL, screenshots VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, logo VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, city VARCHAR(100) NOT NULL, department INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, logo VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, start_year INT NOT NULL, end_year INT DEFAULT NULL, level INT NOT NULL, position INT NOT NULL, display TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, logo VARCHAR(100) NOT NULL, skill_type_id INT NOT NULL, INDEX IDX_5E3DE477DFB912BA (skill_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skill_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, position INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME on update CURRENT_TIMESTAMP, label VARCHAR(120) NOT NULL, logo VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED2C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE experience_skill ADD CONSTRAINT FK_3D6F986146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_skill ADD CONSTRAINT FK_3D6F98615585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_item ADD CONSTRAINT FK_4B0BEA0346E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE477DFB912BA FOREIGN KEY (skill_type_id) REFERENCES skill_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED2C32A47EE');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103979B1AD6');
        $this->addSql('ALTER TABLE experience_skill DROP FOREIGN KEY FK_3D6F986146E90E27');
        $this->addSql('ALTER TABLE experience_skill DROP FOREIGN KEY FK_3D6F98615585C142');
        $this->addSql('ALTER TABLE experience_item DROP FOREIGN KEY FK_4B0BEA0346E90E27');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE477DFB912BA');
        $this->addSql('DROP TABLE arcade_type');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE creation_type');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_skill');
        $this->addSql('DROP TABLE experience_item');
        $this->addSql('DROP TABLE photo_type');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_type');
    }
}
