<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Entity\ExperienceLink;
use App\Enum\LinkType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

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
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        // Link to the parent Experience entity (EasyAdmin will use the __toString() of Experience)
        yield AssociationField::new('experience', 'Expérience liée')
            ->setRequired(true);

        yield TextField::new('title', 'Titre')
            ->setHelp('Exemple : Voir le site en ligne, Code source...');

        // URL field for the index, with a clickable link
        yield UrlField::new('url', 'Adresse URL')
            ->setHelp('Exemple : https://github.com/... ou https://...');

        yield ChoiceField::new('type', 'Nature du lien')
            ->setChoices([
                'Document' => LinkType::DOCUMENT,
                'Fichier PDF' => LinkType::PDF,
                'Lien externe' => LinkType::EXTERNAL,
            ])
            ->formatValue(function ($value, $entity) {
                if ($value instanceof LinkType) {
                    return sprintf(
                        '<i class="fas %s me-2"></i> %s',
                        $value->getIcon(),
                        $value->getLabel()
                    );
                }

                return $value;
            });
    }
}
