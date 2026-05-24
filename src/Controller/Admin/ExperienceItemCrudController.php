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
use App\Entity\ExperienceItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExperienceItemCrudController extends AbstractCrudController
{
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return ExperienceItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Détail d\'expérience')
            ->setEntityLabelInPlural('Détails d\'expériences')
            // Sort by experience first, then by internal position
            ->setDefaultSort([
                'experience' => 'ASC',
                'position' => 'ASC',
            ])
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

        // Liaison avec l'entité parente Experience
        yield AssociationField::new('experience', 'Expérience')
            ->setRequired(true)
            ->setHelp('Sélectionnez le poste ou projet général lié.');

        yield TextField::new('title', 'Titre de la réalisation')
            ->setHelp('Exemple : Refonte de l\'architecture, Lead Dev Symfony, Rôle d\'équilibrage...');

        // Champ texte riche pour décrire précisément la mission / les technos
        yield TextEditorField::new('details', 'Description')
            ->setHelp('Détaillez vos accomplissements majeurs pour ce point.')
            ->hideOnIndex(); // Masqué pour garder le tableau lisible

        // Gestion du picto
        yield TextField::new('picto', 'Picto')
            ->setHelp('Exemple : Copier un emoji.')
            // Un petit rendu visuel sympa sur l'index si c'est une classe FontAwesome
            ->formatValue(function ($value, $entity) {
                if (!$value) {
                    return '<span class="text-muted">Aucun</span>';
                }

                return sprintf('<span class="">%s</span>', htmlspecialchars($value));
            });

        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Permet de classer les détails au sein d\'une même expérience.')
            ->hideOnIndex();
    }
}
