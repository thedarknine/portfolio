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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Project>
 */
class ProjectCrudController extends AbstractCrudController
{
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

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('name', 'Nom du projet');
        yield TextField::new('category', 'Catégorie')
            ->setHelp('Exemple : Développement Web, UX/UI Design, Arcade...');

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

        yield TextField::new('tags', 'Tags (Séparés par ::)')
            ->setHelp('Exemple : Symfony::Tailwind::Docker')
            ->formatValue(function ($value) {
                if (!$value) {
                    return null;
                }
                $tags = explode('::', $value);
                $html = '';
                foreach ($tags as $tag) {
                    $html .= sprintf('<span class="badge bg-secondary me-1">%s</span>', htmlspecialchars(trim($tag)));
                }

                return $html;
            })
            ->hideOnIndex();

        yield TextField::new('screenshots', 'Fichiers Screenshots (Séparés par ::)')
            ->setHelp('Exemple : screen1.png::screen2.png')
            ->hideOnIndex();
    }
}
