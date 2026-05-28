<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\Education;
use App\Enum\EducationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Diplôme')
            ->setEntityLabelInPlural('Diplômes')
            ->setDefaultSort(['year' => 'DESC'])
            ->showEntityActionsInlined()
        ;
    }

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
            ->formatValue(function ($value, $entity) {
                return $value instanceof EducationType ? $value->getLabel() : $value;
            });

        yield TextField::new('details', 'Description succincte / Acquis')
            ->hideOnIndex(); // Hide on index for a cleaner look, but show on forms and detail pages
    }
}
