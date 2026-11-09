<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\SortableCrudTrait;
use App\Entity\ExperienceItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

/**
 * @extends AbstractCrudController<ExperienceItem>
 */
class ExperienceItemCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<ExperienceItem>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return ExperienceItem::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Détail d\'expérience')
            ->setEntityLabelInPlural('Détails d\'expériences')
            // Sort by experience first, then by internal position
            ->setDefaultSort([
                'experience' => 'ASC',
                'position'   => 'ASC',
            ])
            ->showEntityActionsInlined()
        ;
    }

    #[\Override]
    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('experience')->autocomplete());
    }

    #[\Override]
    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->addSortableActions($actions);

        $cancelAction = Action::new('cancel', 'Annuler', 'fa fa-times')
            ->linkToCrudAction(Action::INDEX)
            ->setCssClass('btn btn-warning');

        return $actions
            ->add(Crud::PAGE_EDIT, $cancelAction)
            ->add(Crud::PAGE_NEW, $cancelAction);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        // Link to the parent Experience entity (EasyAdmin will use the __toString() of Experience)
        yield AssociationField::new('experience', 'Expérience')
            ->setRequired(true)
            ->setHelp('Sélectionnez le poste ou projet général lié.');

        yield TextField::new('title', 'Titre de la réalisation')
            ->setHelp('Exemple : Refonte de l\'architecture, Lead Dev Symfony, Rôle d\'équilibrage...');

        yield TextareaField::new('details', 'Description')
            ->setHelp('Détaillez vos accomplissements majeurs pour ce point.')
            ->hideOnIndex(); // Masqué pour garder le tableau lisible

        // Picto: a Hugeicons name picked from a curated list
        yield ChoiceField::new('picto', 'Picto')
            ->setChoices(ExperienceItem::PICTOS)
            ->setHelp('Icône affichée devant le titre sur la page Expérience.');

        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Permet de classer les détails au sein d\'une même expérience.')
            ->hideOnIndex();
    }
}
