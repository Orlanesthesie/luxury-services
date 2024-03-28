<?php

namespace App\Controller\Admin;

use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Experience;
use App\Entity\JobOffer;
use App\Entity\JobType;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CandidatCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Luxury');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Candidat', 'fa fa-otter', Candidat::class);
        yield MenuItem::linkToCrud('Client', 'fas fa-user-tie', Client::class);
        yield MenuItem::linkToCrud('JobOffer', 'fas fa-seedling', JobOffer::class);
        yield MenuItem::linkToCrud('Candidature', 'fas fa-toilet-paper', Candidature::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-sack-dollar', Category::class);
        yield MenuItem::linkToCrud('Experience', 'fas fa-clock', Experience::class);
        yield MenuItem::linkToCrud('User', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('JobType', 'fas fa-snowman', JobType::class);
    }
}
