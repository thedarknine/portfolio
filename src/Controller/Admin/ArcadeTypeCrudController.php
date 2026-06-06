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
use App\Entity\ArcadeType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<ArcadeType>
 */
class ArcadeTypeCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<ArcadeType>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return ArcadeType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie Arcade')
            ->setEntityLabelInPlural('Catégories Arcade')
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

        yield TextField::new('name', 'Nom de la catégorie')
            ->setHelp('Exemple : Matériel, Électronique, Systèmes de jeux, Décoration...');

        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Définit l\'ordre d\'apparition sur le site.')
            ->hideOnIndex();
    }
}
