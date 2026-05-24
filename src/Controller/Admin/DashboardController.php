<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Clean redirection: official method recommended by the creator of EasyAdmin
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ExperienceCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Portfolio - Admin')
            ->setDefaultColorScheme('dark');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home'),

            MenuItem::section('Parcours'),
            MenuItem::linkTo(ExperienceCrudController::class, 'Expériences', 'fas fa-briefcase'),
            MenuItem::linkTo(SkillTypeCrudController::class, 'Types de compétences', 'fa fa-tags'),
            MenuItem::linkTo(SkillCrudController::class, 'Compétences', 'fa fa-code'),

            MenuItem::section('Cursus & Formations'),
            MenuItem::linkTo(SchoolCrudController::class, 'Établissements', 'fa fa-building'),
            MenuItem::linkTo(EducationCrudController::class, 'Diplômes', 'fa fa-graduation-cap'),

            MenuItem::section('Retour'),
            MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_index'),
        ];
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile('styles/admin.css')
        ;
    }
}
