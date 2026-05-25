<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\SortableCrudTrait;
use App\Enum\PageCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PageCrudController extends AbstractCrudController
{
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Contenu de Page')
            ->setEntityLabelInPlural('Contenus de Pages')
            ->setDefaultSort(['position' => 'ASC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->addSortableActions($actions);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('title', 'Page')
            ->setHelp('Exemple : Accueil, À propos, Créations...');

        yield ChoiceField::new('category', 'Catégorie')
            ->setChoices([
                'Parcours' => PageCategory::CAREER,
                'Centre d\'intérêt' => PageCategory::INTEREST,
            ])
            ->formatValue(function ($value, $entity) {
                return $value instanceof PageCategory ? $value->getLabel() : $value;
            });

        yield IntegerField::new('position', 'Ordre d\'affichage')
            ->setRequired(false)
            ->setHelp('Plus le chiffre est bas, plus il apparaît en premier.');

        // Configure slug linked to 'title' for automatic generation
        yield SlugField::new('slug', 'Slug (URL)')
            // Based on 'title' for automatic generation in JS
            ->setTargetFieldName('title')
            ->setHelp('Identifiant unique pour l\'URL (généré automatiquement à partir du titre).')
            // Optional: make it editable or not
            ->setFormTypeOption('disabled', false);

        yield TextField::new('technicalName', 'Nom technique')
            ->setHelp('Identifiant interne (ex: "home", "about", "creations").');

        yield TextField::new('tagline', 'Tagline')
            ->setHelp('La phrase d\'introduction principale de la page.');

        yield BooleanField::new('inHeader', 'Afficher dans le header')
            ->setHelp('Si activé, cette page apparaîtra dans le menu de navigation principal du site.')
            ->renderAsSwitch(true);

        // TextareaField est plus adapté ici qu'un éditeur HTML lourd pour des sous-titres ou blocs courts
        yield TextareaField::new('subtitle', 'Sous-titre')
            ->setHelp('Texte complémentaire sous le titre principal.')
            ->hideOnIndex();

        yield TextareaField::new('quote', 'Citation')
            ->setHelp('Une phrase inspirante, une note d\'intention (ex: pour la section Photos).')
            ->hideOnIndex();
    }
}
