<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../src',
        __DIR__ . '/../tests',
    ])
    ->withSkip([
        __DIR__ . '/../src/Kernel.php',
        __DIR__ . '/../tests/bootstrap.php',
        __DIR__ . '/../var',
        __DIR__ . '/../vendor',
        __DIR__ . '/../public',
        __DIR__ . '/../.tools-config',
        __DIR__ . '/../src/DataFixtures/Testing',
    ])
    
    ->withPhpSets(
        php84: true,
    )
    ->withSets([
        SymfonySetList::SYMFONY_80,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,

        PHPUnitSetList::PHPUNIT_100,

        SetList::TYPE_DECLARATION,
        SetList::PRIVATIZATION,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        // naming: true,
        // privatization: true,
        // typeDeclarations: false,
        earlyReturn: true,
        rectorPreset: false,
    );