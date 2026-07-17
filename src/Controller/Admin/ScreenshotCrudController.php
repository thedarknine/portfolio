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
use App\Entity\Screenshot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Screenshot>
 */
class ScreenshotCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<Screenshot>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Screenshot::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Capture d\'écran')
            ->setEntityLabelInPlural('Captures d\'écran')
            ->setDefaultSort(['position' => 'ASC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('filename', 'Nom du fichier')
            ->setHelp('Exemple : screenshot-1.png (doit être présent dans public/images/projects/)')
            ->setColumns(12);

        yield TextField::new('description', 'Description')
            ->setHelp('Description de la capture d\'écran')
            ->setColumns(12);

        yield IntegerField::new('position', 'Ordre d\'affichage')
            ->setRequired(false)
            ->setHelp('Plus le chiffre est bas, plus il apparaît en premier.')
            ->setColumns(12)
            ->hideOnIndex();
    }
}
