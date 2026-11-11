<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Education;
use App\Enum\EducationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Education>
 */
class EducationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Education::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Diplôme')
            ->setEntityLabelInPlural('Diplômes')
            ->setDefaultSort(['year' => 'DESC'])
            ->showEntityActionsInlined()
        ;
    }

    #[\Override]
    public function configureActions(Actions $actions): Actions
    {
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

        yield IntegerField::new('year', 'Obtention')
            ->setFormTypeOption('attr', ['min' => 1990, 'max' => 2030]);

        yield TextField::new('title', 'Intitulé')
            ->setHelp('Exemple : Master 2 Stratégie Internet, Scrum Product Owner...');

        yield TextField::new('speciality', 'Spécialité')
            ->setHelp('Optionnel (ex: Gestion de projet, Informatique...)')
            ->hideOnIndex();

        yield TextField::new('mention', 'Mention / Note')
            ->setHelp('Optionnel (ex: Bien, Félicitations du jury...)')
            ->hideOnIndex();

        // Relation with School (EasyAdmin will use the __toString() of School)
        yield AssociationField::new('school', 'Établissement')
            ->setRequired(true);

        // Manage the enum with a ChoiceField for better UX in the admin
        yield ChoiceField::new('type', 'Type de cursus')
            ->setChoices([
                'Universitaire' => EducationType::UNIVERSITARY,
                'Professionnel' => EducationType::PROFESSIONAL,
            ])
            ->formatValue(fn ($value, $entity) => $value instanceof EducationType ? $value->getLabel() : $value);

        yield TextField::new('details', 'Description succincte / Acquis')
            ->hideOnIndex(); // Hide on index for a cleaner look, but show on forms and detail pages

        yield TextareaField::new('takeaway', 'Ce que j\'en retiens')
            ->setHelp('Optionnel : une ou deux phrases sur ce que la formation a apporté.')
            ->hideOnIndex();

        yield TextField::new('linkLabel', 'Libellé du lien')
            ->setHelp('Optionnel (ex : Voir l\'étude de cas Lydia).')
            ->hideOnIndex();

        yield TextField::new('linkUrl', 'URL du lien')
            ->setHelp('Optionnel : une URL complète ou un chemin du site (ex : /experience#stage-limos).')
            ->hideOnIndex();
    }
}
