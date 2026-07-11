<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Entity\ExperienceLink;
use App\Entity\PageInfo;
use App\Enum\LinkType;
use App\Repository\PageInfoRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * @extends AbstractCrudController<ExperienceLink>
 */
class ExperienceLinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExperienceLink::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Lien d\'expérience')
            ->setEntityLabelInPlural('Liens d\'expériences')
            ->setDefaultSort(['experience' => 'ASC'])
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

        // Link to the parent Experience entity (EasyAdmin will use the __toString() of Experience)
        yield AssociationField::new('experience', 'Expérience liée')
            ->setRequired(true)
            ->setFormTypeOption(
                'choice_label',
                fn (Experience $experience) => $experience->getDisplayName(),
            )
            ->formatValue(
                fn (?Experience $experience) => $experience?->getDisplayName(),
            );

        yield TextField::new('title', 'Titre')
            ->setHelp('Exemple : Voir le site en ligne, Code source...');

        yield SlugField::new('slug', 'Slug (URL)')
            ->setTargetFieldName('title')
            ->setFormTypeOption('disabled', false)
            ->hideOnIndex();

        yield ChoiceField::new('type', 'Nature du lien')
            ->setChoices([
                'Document'     => LinkType::DOCUMENT,
                'Fichier PDF'  => LinkType::PDF,
                'Lien externe' => LinkType::EXTERNAL,
                'Lien interne' => LinkType::INTERNAL,
                'Détail'       => LinkType::DETAIL,
            ])
            ->formatValue(function ($value, $entity) {
                if ($value instanceof LinkType) {
                    return sprintf(
                        '<i class="fas %s me-2"></i> %s',
                        $value->getIcon(),
                        $value->getLabel(),
                    );
                }
            })
            ->renderExpanded(false);

        yield UrlField::new('url')
            ->setHelp('Requis sauf si le type de lien est "Détail" (dans ce cas, sélectionnez une page ci-dessous).');

        yield AssociationField::new('page', 'Page liée')
            ->setFormTypeOption('choice_label', function (PageInfo $page) {
                return $page->getParent()
                    ? $page->getParent()->getTitle() . ' > ' . $page->getTitle()
                    : $page->getTitle();
            })
            ->setFormTypeOption('query_builder', function (PageInfoRepository $repository) {
                return $repository->createQueryBuilder('p')
                    ->orderBy('p.position', 'ASC');
            })
            ->setHelp('Uniquement requis si le type de lien est "Détail".');
    }
}
