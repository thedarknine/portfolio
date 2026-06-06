<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Company>
 */
class CompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Entreprise')
            ->setEntityLabelInPlural('Entreprises')
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

        // Use the NameableTrait
        yield TextField::new('name', 'Nom de l\'entreprise')
            ->setHelp('Exemple : Leviia, Perfect, Coffreo...');

        // Logo Management (Text file name + HTML preview)
        yield TextField::new('logo', 'Fichier Logo')
            ->setHelp('Exemple : logo-leviia.svg (doit être dans public/uploads/companies/)')
            ->formatValue(function ($value, $entity) {
                if (!$value) {
                    return '<span class="text-muted">Aucun</span>';
                }

                return sprintf(
                    '<img src="/images/company/%s" class="bg-white rounded" style="max-height: 30px; max-width: 30px; object-fit: contain;" alt="%s">',
                    $value,
                    htmlspecialchars($entity->getName()),
                );
            });

        yield TextField::new('city', 'Ville')
            ->hideOnIndex();

        yield IntegerField::new('department', 'Département')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 976])
            ->hideOnIndex();
    }
}
