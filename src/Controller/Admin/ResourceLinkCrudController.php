<?php

namespace App\Controller\Admin;

use App\Entity\ResourceLink;
use App\Controller\Admin\Trait\SortableCrudTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ResourceLinkCrudController extends AbstractCrudController
{
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
        return $this->addSortableActions($actions);
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
                if (!$value) return null;
                return sprintf('<a href="%s" target="_blank" rel="noopener"><i class="fa fa-external-link-alt me-1"></i> Ouvrir</a>', htmlspecialchars($value));
            });

        // Use IconableTrait: display the CSS class with a small preview of the FontAwesome icon
        yield TextField::new('icon', 'Classe Icône')
            ->setHelp('Exemple : fa-github, fa-linkedin, fa-file-pdf (FontAwesome)')
            ->formatValue(function ($value) {
                if (!$value) return '<span class="text-muted">Aucune</span>';
                return sprintf('<i class="fab %1$s fas %1$s fa-lg me-2"></i> <code>%1$s</code>', htmlspecialchars($value));
            });

        // Switch to display in the Hero on homepage
        yield BooleanField::new('inHero', 'Afficher dans le Hero')
            ->setHelp('Si activé, ce lien apparaîtra sous forme d\'icône directement dans l\'en-tête principal du site.')
            ->renderAsSwitch(true);

        // Use SortableTrait: hidden in form to let Gedmo place it at the end automatically
        yield IntegerField::new('position', 'Ordre')
            ->setRequired(false)
            ->hideOnIndex()
            ->hideOnForm();
    }
}
