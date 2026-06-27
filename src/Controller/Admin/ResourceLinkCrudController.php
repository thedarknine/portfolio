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
use App\Entity\ResourceLink;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * @extends AbstractCrudController<ResourceLink>
 */
class ResourceLinkCrudController extends AbstractCrudController
{
    /**
     * @use SortableCrudTrait<ResourceLink>
     */
    use SortableCrudTrait;

    public static function getEntityFqcn(): string
    {
        return ResourceLink::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Resssource')
            ->setEntityLabelInPlural('Ressources')
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

        yield TextField::new('title', 'Nom de la ressource')
            ->setHelp('Exemple : GitHub, LinkedIn, Mon CV...');

        yield SlugField::new('slug', 'Slug (URL)')
            ->setTargetFieldName('title')
            ->setFormTypeOption('disabled', false)
            ->hideOnIndex();

        yield UrlField::new('url', 'Lien URL')
            ->setHelp('Exemple : https://github.com/carolinenoyer')
            ->formatValue(function ($value) {
                if (!$value) {
                    return null;
                }

                return sprintf('<a href="%s" target="_blank" rel="noopener"><i class="fa7-brands:linkedin-in me-1"></i> Ouvrir</a>', htmlspecialchars($value));
            });

        // Use IconableTrait: display the CSS class with a small preview of the FontAwesome icon
        yield TextField::new('icon', 'Classe Icône')
            ->setHelp('Exemple : flowbite:linkedin-solid (<a href="https://ux.symfony.com/icons" target="_blank">Voir la documentation</a>)')
            ->formatValue(function ($value) {
                if (!$value) {
                    return '<span class="text-muted">Aucune</span>';
                }

                return sprintf('<code>%1$s</code>', htmlspecialchars($value));
            });

        // Switch to display in the Hero on homepage
        yield BooleanField::new('inHero', 'Hero')
            ->setHelp('Si activé, ce lien apparaîtra sous forme d\'icône directement dans l\'en-tête principal du site.')
            ->renderAsSwitch(true);

        // Switch to publish/unpublish
        yield BooleanField::new('published', 'Publié')
            ->setHelp('Si activé, ce lien sera visible sur le site.')
            ->renderAsSwitch(true);

        // Use SortableTrait: hidden in form to let Gedmo place it at the end automatically
        yield IntegerField::new('position', 'Ordre')
            ->setRequired(false)
            ->hideOnIndex();
    }
}
