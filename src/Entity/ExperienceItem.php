<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Entity\Interface\SortableEntityInterface;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Traits\TitleableTrait;
use App\Repository\ExperienceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExperienceItemRepository::class)]
class ExperienceItem implements SortableEntityInterface
{
    use TimeStampableTrait;
    use TitleableTrait;
    use SortableTrait;
    /** Hugeicons available for an item, label => icon name (rendered with ux_icon). */
    public const array PICTOS = [
        'Lien, interface'           => 'hugeicons:link-01',
        'Pilotage'                  => 'hugeicons:compass-01',
        'Équipe'                    => 'hugeicons:user-group',
        'Collaboration'             => 'hugeicons:agreement-01',
        'Structuration'             => 'hugeicons:puzzle',
        'Cadrage, organisation'     => 'hugeicons:task-01',
        'Spécifications, rédaction' => 'hugeicons:note-edit',
        'Discovery, objectifs'      => 'hugeicons:target-02',
        'Lancement'                 => 'hugeicons:rocket-01',
        'Résultats, croissance'     => 'hugeicons:chart-increase',
        'Conception, réflexion'     => 'hugeicons:brain',
        'Qualité, recette'          => 'hugeicons:check-list',
        'Sécurité, conformité'      => 'hugeicons:shield-01',
        'Automatisation'            => 'hugeicons:settings-02',
        'Workflow'                  => 'hugeicons:workflow-square-03',
        'Outillage'                 => 'hugeicons:wrench-01',
        'Développement'             => 'hugeicons:laptop-programming',
        'API, intégration'          => 'hugeicons:plug-01',
        'Web'                       => 'hugeicons:globe-02',
        'Mobile'                    => 'hugeicons:smart-phone-01',
        'Système, infrastructure'   => 'hugeicons:computer',
        'Design, interface'         => 'hugeicons:paint-board',
        'Zoning, maquette'          => 'hugeicons:pencil-edit-01',
        'Analyse, recherche'        => 'hugeicons:search-01',
        'Document'                  => 'hugeicons:file-01',
        'Recherche scientifique'    => 'hugeicons:test-tube',
        'Publication'               => 'hugeicons:book-open-01',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Details cannot be empty.')]
    private ?string $details = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Picto cannot be empty.')]
    #[Assert\Choice(choices: self::PICTOS, message: 'Picto must be one of the available icons.')]
    private ?string $picto = null;

    // Positions are counted per experience, not across all items
    #[Gedmo\SortableGroup]
    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Experience is required.')]
    private ?Experience $experience = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getPicto(): ?string
    {
        return $this->picto;
    }

    public function setPicto(string $picto): static
    {
        $this->picto = $picto;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): static
    {
        $this->experience = $experience;

        return $this;
    }
}
