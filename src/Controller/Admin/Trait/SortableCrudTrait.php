<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin\Trait;

use App\Entity\Interface\SortableEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template TEntity of SortableEntityInterface
 */
trait SortableCrudTrait
{
    /**
     * Add sortable actions (move up, move down, move top, move bottom) to the given Actions object.
     */
    protected function addSortableActions(Actions $actions): Actions
    {
        $moveUp = Action::new('moveUp', false, 'fa fa-arrow-up')
            ->setHtmlAttributes(['title' => 'Monter'])
            ->linkToCrudAction('moveUp');

        $moveDown = Action::new('moveDown', false, 'fa fa-arrow-down')
            ->setHtmlAttributes(['title' => 'Descendre'])
            ->linkToCrudAction('moveDown');

        $moveTop = Action::new('moveTop', false, 'fa fa-angles-up')
            ->setHtmlAttributes(['title' => 'Mettre en premier'])
            ->linkToCrudAction('moveTop');

        $moveBottom = Action::new('moveBottom', false, 'fa fa-angles-down')
            ->setHtmlAttributes(['title' => 'Mettre en dernier'])
            ->linkToCrudAction('moveBottom');

        return $actions
            ->add(Crud::PAGE_INDEX, $moveUp)
            ->add(Crud::PAGE_INDEX, $moveDown)
            ->add(Crud::PAGE_INDEX, $moveTop)
            ->add(Crud::PAGE_INDEX, $moveBottom)
        ;
    }

    /**
     * @param AdminContext<TEntity> $context
     */
    #[AdminRoute('/move-up', name: 'moveUp')]
    public function moveUp(AdminContext $context, EntityManagerInterface $em, AdminUrlGenerator $aug): Response
    {
        return $this->updateSortablePosition($context, $em, $aug, -1);
    }

    /**
     * @param AdminContext<TEntity> $context
     */
    #[AdminRoute('/move-down', name: 'moveDown')]
    public function moveDown(AdminContext $context, EntityManagerInterface $em, AdminUrlGenerator $aug): Response
    {
        return $this->updateSortablePosition($context, $em, $aug, 1);
    }

    /**
     * @param AdminContext<TEntity> $context
     */
    #[AdminRoute('/move-top', name: 'moveTop')]
    public function moveTop(AdminContext $context, EntityManagerInterface $em, AdminUrlGenerator $aug): Response
    {
        return $this->updateSortablePosition($context, $em, $aug, 0, true);
    }

    /**
     * @param AdminContext<TEntity> $context
     */
    #[AdminRoute('/move-bottom', name: 'moveBottom')]
    public function moveBottom(AdminContext $context, EntityManagerInterface $em, AdminUrlGenerator $aug): Response
    {
        return $this->updateSortablePosition($context, $em, $aug, -1, true);
    }

    /**
     * @param AdminContext<TEntity> $context
     */
    private function updateSortablePosition(AdminContext $context, EntityManagerInterface $em, AdminUrlGenerator $urlGenerator, int $offset, bool $absolute = false): Response
    {
        // Thanks to polymorphism, $entity dynamically gets the current object (Skill, SkillType...)
        $entity = $context->getEntity()->getInstance();

        if ($entity instanceof SortableEntityInterface) {
            if ($absolute) {
                $entity->setPosition($offset);
            } else {
                $entity->setPosition($entity->getPosition() + $offset);
            }

            $em->flush();
        }

        // static::class allows to resolve the child controller calling instead of the Trait itself
        $url = $urlGenerator->setController(static::class)->setAction(Crud::PAGE_INDEX)->generateUrl();

        return $this->redirect($url);
    }
}
