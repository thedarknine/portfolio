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
use App\Entity\PhotoType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<PhotoType>
 */
class PhotoTypeCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<PhotoType>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return PhotoType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type de photo')
            ->setEntityLabelInPlural('Types de photos')
            ->setDefaultSort(['position' => 'ASC'])
            ->showEntityActionsInlined()
        ;
    }

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

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('name', 'Nom de la thématique')
            ->setHelp('Exemple : Paysages, Portraits, Noir & Blanc, Urbain...');

        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Définit l\'ordre d\'apparition sur le site.')
            ->hideOnIndex();
    }
}
