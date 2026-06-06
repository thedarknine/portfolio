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
use App\Entity\Skill;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

/**
 * @extends AbstractCrudController<Skill>
 */
class SkillCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<Skill>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return Skill::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Compétence')
            ->setEntityLabelInPlural('Compétences')
            // Sort by skillType first, then by position
            ->setDefaultSort([
                'skillType' => 'ASC',
                'position'  => 'ASC',
            ])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('skillType')->autocomplete());
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

        yield TextField::new('name', 'Nom')
            ->setHelp('Exemple : PHP, Symfony, Docker, Figma...');

        // Relation with SkillType (EasyAdmin will use the __toString() of SkillType)
        yield AssociationField::new('skillType', 'Catégorie')
            ->setRequired(true)
            ->setHelp('Sélectionnez le type de compétence correspondant.');

        yield TextField::new('logo', 'Logo')
            ->setHelp('Exemple : skills-sysadmin.png (doit être présent dans public/images/skills/)')
            ->formatValue(function ($value, $entity) {
                // If no file is provided in the database, return a placeholder or empty string
                if (!$value) {
                    return '<span class="text-muted">Aucun</span>';
                }

                // Generate a proper <img> HTML tag for the Index page
                return sprintf(
                    '<img src="/images/skills/%s" class="bg-white rounded" style="max-height: 30px; max-width: 30px; object-fit: contain;" alt="%s">',
                    $value,
                    htmlspecialchars($entity->getName()),
                );
            });

        yield IntegerField::new('level', 'Niveau')
            ->setHelp('Note de maîtrise (ex: 10 pour un niveau expert sur 10)');

        yield IntegerField::new('position', 'Ordre')
            ->setHelp('Permet de classer les compétences au sein d\'une même catégorie.')
            ->hideOnIndex();

        yield IntegerField::new('startYear', 'Année de début')
            ->setHelp('Année où vous avez commencé à utiliser cette techno.')
            ->hideOnIndex();

        yield IntegerField::new('endYear', 'Année de fin')
            ->setHelp('Laissez vide si vous l\'utilisez toujours actuellement.')
            ->hideOnIndex(); // Avoid confusion on the index page with empty values

        yield BooleanField::new('display', 'Afficher')
            ->setHelp('Compétence visible sur le site.')
            ->renderAsSwitch(true);
    }
}
