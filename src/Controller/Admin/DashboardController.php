<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller\Admin;

use App\Service\AdminDashboardService;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminDashboardService $dashboard)
    {
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => $this->dashboard->getStats(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Portfolio - Admin')
            ->setDefaultColorScheme('dark');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Gestion Éditoriale');
        yield MenuItem::linkTo(PageInfoCrudController::class, 'Pages du site', 'fa fa-file-lines')->setAction(Action::INDEX);
        yield MenuItem::linkTo(ResourceLinkCrudController::class, 'Ressources', 'fa-solid fa-box-open')->setAction(Action::INDEX);

        yield MenuItem::section('Parcours');
        yield MenuItem::linkTo(CompanyCrudController::class, 'Entreprises', 'fa fa-building-user')->setAction(Action::INDEX);
        yield MenuItem::linkTo(ExperienceCrudController::class, 'Expériences', 'fas fa-briefcase')->setAction(Action::INDEX);
        yield MenuItem::linkTo(ExperienceItemCrudController::class, 'Missions', 'fa fa-list-check')->setAction(Action::INDEX);
        yield MenuItem::linkTo(ExperienceLinkCrudController::class, 'Liens', 'fa fa-link')->setAction(Action::INDEX);

        yield MenuItem::section('Compétences');
        yield MenuItem::linkTo(SkillTypeCrudController::class, 'Types', 'fa fa-tags')->setAction(Action::INDEX);
        yield MenuItem::linkTo(SkillCrudController::class, 'Compétences', 'fa fa-code')->setAction(Action::INDEX);

        yield MenuItem::section('Réalisations');
        yield MenuItem::linkTo(ProjectCrudController::class, 'Projets', 'fa fa-diagram-project')->setAction(Action::INDEX);

        yield MenuItem::section('Cursus & Formations');
        yield MenuItem::linkTo(SchoolCrudController::class, 'Établissements', 'fa fa-building')->setAction(Action::INDEX);
        yield MenuItem::linkTo(EducationCrudController::class, 'Diplômes', 'fa fa-graduation-cap')->setAction(Action::INDEX);

        yield MenuItem::section('Hobbies');
        yield MenuItem::linkTo(ArcadeTypeCrudController::class, 'Arcade > Catégories', 'fa-solid fa-gamepad')->setAction(Action::INDEX);
        yield MenuItem::linkTo(CreationTypeCrudController::class, 'Types > Créations', 'fa fa-brush')->setAction(Action::INDEX);
        yield MenuItem::linkTo(PhotoTypeCrudController::class, 'Thématiques > Photos', 'fa fa-camera')->setAction(Action::INDEX);

        yield MenuItem::section('Retour');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_index');
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile('styles/admin.css')
        ;
    }
}
