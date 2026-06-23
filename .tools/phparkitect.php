<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\Extend;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\NotDependsOnTheseNamespaces;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    // 1. Define the set of files to analyze
    $classSet = ClassSet::fromDir(__DIR__ . '/../src');

    $rules = [];

    // Rule 1 : Commands should have the suffix "Command"
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Command'))
        ->should(new HaveNameMatching('*Command'))
        ->because('Standardization of command naming.');

    // Rule 2 : Repositories should have the suffix "Repository"
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Repository'))
        ->should(new HaveNameMatching('*Repository'))
        ->because('Standardization of repository naming.');

    // Rule 3 : Repositories should extend ServiceEntityRepository
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Repository'))
        ->should(new Extend('Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository'))
        ->because('All repositories should extend ServiceEntityRepository.');

    // Rule 4 : Entities isolation - Entities should not depend on other application layers
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Entity'))
        ->should(new NotDependsOnTheseNamespaces(['App\Command', 'App\Controller']))
        ->because('Entities are the business core and should not depend on transport layers (CLI/Web).');

    // Rule 5 : Fixtures should extend Doctrine\Bundle\FixturesBundle\Fixture
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\DataFixtures'))
        ->should(new Extend('Doctrine\Bundle\FixturesBundle\Fixture'))
        ->because('All fixtures must extend Doctrine\Bundle\FixturesBundle\Fixture to be loaded by Doctrine.');

    // Add all rules to the configuration
    foreach ($rules as $rule) {
        $config->add($classSet, $rule);
    }
};
