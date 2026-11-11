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
 * Replace the emoji pictos of experience items with Hugeicons names.
 */
final class Version20261108230000 extends AbstractMigration
{
    private const array MAP = [
        '🔗' => 'hugeicons:link-01',
        '🧩' => 'hugeicons:puzzle',
        '⚙️' => 'hugeicons:settings-02',
        '⚙'  => 'hugeicons:settings-02',
        '🚀' => 'hugeicons:rocket-01',
        '🧠' => 'hugeicons:brain',
        '🎯' => 'hugeicons:target-02',
        '📈' => 'hugeicons:chart-increase',
        '👥' => 'hugeicons:user-group',
        '📱' => 'hugeicons:smart-phone-01',
        '🧭' => 'hugeicons:compass-01',
        '📝' => 'hugeicons:note-edit',
        '🤝' => 'hugeicons:agreement-01',
        '🎨' => 'hugeicons:paint-board',
        '💻' => 'hugeicons:laptop-programming',
        '🔌' => 'hugeicons:plug-01',
        '🛠️' => 'hugeicons:wrench-01',
        '🛠'  => 'hugeicons:wrench-01',
        '🌐' => 'hugeicons:globe-02',
        '🖥️' => 'hugeicons:computer',
        '🖥'  => 'hugeicons:computer',
        '📋' => 'hugeicons:task-01',
        '📄' => 'hugeicons:file-01',
        '🔍' => 'hugeicons:search-01',
        '✏️' => 'hugeicons:pencil-edit-01',
        '✏'  => 'hugeicons:pencil-edit-01',
        '🔬' => 'hugeicons:test-tube',
        '📚' => 'hugeicons:book-open-01',
    ];

    public function getDescription(): string
    {
        return 'Experience items: emoji pictos replaced with Hugeicons names';
    }

    public function up(Schema $schema): void
    {
        // BINARY: utf8mb4_unicode_ci compares most emojis as equal
        foreach (self::MAP as $emoji => $icon) {
            $this->addSql('UPDATE experience_item SET picto = :icon WHERE BINARY picto = :emoji', ['icon' => $icon, 'emoji' => $emoji]);
        }
        // Anything left (unknown emoji or text) gets a neutral icon
        $this->addSql("UPDATE experience_item SET picto = 'hugeicons:check-list' WHERE picto NOT LIKE 'hugeicons:%'");
    }

    public function down(Schema $schema): void
    {
        foreach (self::MAP as $emoji => $icon) {
            $this->addSql('UPDATE experience_item SET picto = :emoji WHERE BINARY picto = :icon', ['icon' => $icon, 'emoji' => $emoji]);
        }
    }
}
