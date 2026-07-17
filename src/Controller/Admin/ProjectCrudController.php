<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\Screenshot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

/**
 * @extends AbstractCrudController<Project>
 */
class ProjectCrudController extends AbstractCrudController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projets')
            ->setDefaultSort(['year' => 'DESC'])
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

        yield TextField::new('name', 'Nom du projet');
        yield TextField::new('category', 'Catégorie')
            ->setHelp('Exemple : Développement Web, UX/UI Design, Arcade...');

        yield SlugField::new('slug', 'Slug (URL)')
            ->setTargetFieldName('name')
            ->setFormTypeOption('disabled', false)
            ->hideOnIndex();

        yield TextField::new('logo', 'Fichier Logo')
            ->setHelp('Exemple : logo-project.png (dans public/images/projects/)')
            ->formatValue(function ($value, $entity) {
                if (!$value) {
                    return '<span class="text-muted">Aucun</span>';
                }

                return sprintf(
                    '<img src="/images/projects/%s" class="bg-white rounded" style="max-height: 30px; max-width: 30px; object-fit: contain;" alt="%s">',
                    $value,
                    htmlspecialchars($entity->getName()),
                );
            });

        yield IntegerField::new('year', 'Année')
            ->setFormTypeOption('attr', ['min' => 2000, 'max' => 2030]);

        yield BooleanField::new('published', 'Publié')
            ->setFormTypeOption('attr', ['checked' => true]);

        yield TextField::new('period', 'Période')
            ->setHelp('Exemple : Janvier - Mars ou 6 mois')
            ->hideOnIndex();

        yield TextEditorField::new('description', 'Description du projet')
            ->hideOnIndex();

        yield AssociationField::new('tags')
            ->autocomplete()
            ->setHelp(sprintf(
                'Vous ne trouvez pas le tag ? <a href="%s" target="_blank">Créer un nouveau tag</a>',
                $this->adminUrlGenerator
                    ->setController(ProjectTagCrudController::class)
                    ->setAction(Action::NEW)
                    ->generateUrl(),
            ));

        yield CollectionField::new('screenshots', 'Fichiers Screenshots')
            ->useEntryCrudForm(ScreenshotCrudController::class)
            ->setHelp('Ajoutez les fichiers screenshots du projet')
            ->setEntryToStringMethod(
                fn (Screenshot $screenshot): string => $screenshot->getFilename() ?? 'Nouveau screenshot',
            )
            ->hideOnIndex();
    }
}
