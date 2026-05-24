<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\Experience;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExperienceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Experience::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Expérience')
            ->setEntityLabelInPlural('Expériences')
            ->setDefaultSort([
                'startDate' => 'DESC',
            ])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Hide the ID on forms, but show it on the index page
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('title', 'Intitulé du poste');
        yield TextField::new('subtitle', 'Sous-titre / Complément');

        // Relation with the company (EasyAdmin will use the __toString() of Company)
        yield AssociationField::new('company', 'Entreprise');

        yield DateField::new('startDate', 'Date de début')->setFormat('yyyy-MM')->hideOnIndex();
        yield DateField::new('endDate', 'Date de fin')->setFormat('yyyy-MM')->setRequired(false)->hideOnIndex();

        yield TextEditorField::new('summary', 'Résumé (court)')->hideOnIndex();
        yield TextEditorField::new('description', 'Missions / Description globale')->hideOnIndex();

        // Relation with skills (EasyAdmin will use the __toString() of Skill)
        yield AssociationField::new('skills', 'Compétences associées')->hideOnIndex();
    }
}
