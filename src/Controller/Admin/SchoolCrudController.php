<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<School>
 */
class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('École')
            ->setEntityLabelInPlural('Écoles')
            ->setDefaultSort(['name' => 'ASC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $cancelAction = Action::new('cancel', 'Annuler', 'fa fa-times')
            ->linkToCrudAction(Action::INDEX)
            ->setCssClass('btn btn-warning');

        return $actions
            ->add(Crud::PAGE_EDIT, $cancelAction)
            ->add(Crud::PAGE_NEW, $cancelAction);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('name', 'Nom de l\'établissement')
            ->setHelp('Exemple : Université Blaise Pascal, The Design Crew...');

        // Management of the logo with a simple TextField + HTML preview on index
        yield TextField::new('logo', 'Nom du fichier Logo')
            ->setHelp('Exemple : logo-ubp.png (doit être dans public/uploads/schools/)')
            ->formatValue(function ($value, $entity) {
                if (!$value) {
                    return '<span class="text-muted">Aucun</span>';
                }

                // Render an HTML img tag to display the logo in the index page, with some styling to keep it neat
                return sprintf(
                    '<img src="/images/education/%s" class="bg-white rounded" style="max-height: 30px; max-width: 30px; object-fit: contain;" alt="%s">',
                    $value,
                    htmlspecialchars($entity->getName()),
                );
            });

        yield TextField::new('city', 'Ville');

        yield IntegerField::new('department', 'Département')
            ->setHelp('Exemple : 63, 75...')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 976]);
    }
}
