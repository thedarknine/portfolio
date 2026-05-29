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
use App\Entity\SkillType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

/**
 * @extends AbstractCrudController<SkillType>
 */
class SkillTypeCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<SkillType>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return SkillType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type de compétence')
            ->setEntityLabelInPlural('Types de compétences')

            ->setDefaultSort(['position' => 'ASC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(BooleanFilter::new('deleted'));
    }

    /*public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Remove soft-deleted items from the list (if you use soft delete)
        $qb->andWhere($qb->getRootAliases()[0].'.deleted = :deleted')
            ->setParameter('deleted', false);

        return $qb;
    }*/

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('name', 'Nom du type')
            ->setHelp('Exemple: Back-end, Front-end, Gestion de projet...');

        yield IntegerField::new('position', 'Ordre d\'affichage')
            ->setHelp('Plus le chiffre est bas, plus il apparaît en premier.');

        yield TextField::new('logo', 'Logo')->setHelp('Doit correspondre au nom du fichier dans public/uploads/skills/ (ex: skills-sysadmin.png)')
            ->hideOnIndex(); // Avoid cluttering the index page with logo paths

        yield TextEditorField::new('description', 'Description')
            ->setNumOfRows(5)
            ->hideOnIndex();

        // Allows to display or link the Skills associated with this type
        yield AssociationField::new('skills', 'Nb compétences liées')
            ->onlyOnIndex(); // Display only on index as a count, not the full list of skills (which can be too long)

        // To manage soft delete
        yield BooleanField::new('deleted', 'Archivé');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->addSortableActions($actions);
    }
}
