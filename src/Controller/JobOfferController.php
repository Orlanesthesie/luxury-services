<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\JobOffer;
use App\Form\JobOffer1Type;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job/offer')]
class JobOfferController extends AbstractController
{
    #[Route('/jobOffers', name: 'app_jobOffer')]
    public function jobOffers(JobOfferRepository $jobOfferRepository): Response
    {
        $jobOffers = $jobOfferRepository->findAll();

        return $this->render(
            'job_offer/jobOffer.html.twig',
            [
                'jobOffers' => $jobOffers,
            ]
        );
    }

    #[Route('/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(JobOffer $jobOffer, Category $category): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'job' => $jobOffer,
            'category' => $category,
        ]);
    }
}
