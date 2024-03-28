<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\JobOffer;
use App\Form\CandidatureType;
use App\Repository\CandidatRepository;
use App\Repository\CandidatureRepository;
use App\Repository\JobOfferRepository;
use DateTimeImmutable;
use Doctrine\ORM\Cache\TimestampRegion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    #[Route('/', name: 'app_candidature_index', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CandidatRepository $candidatRepository, JobOfferRepository $jobOfferRepository): Response
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);


        $jobOfferID = $request->request->get('jobOfferId');

        $user = $this->getUser();
        $candidat = $user->getCandidat();
        $candidatID = $candidat->getId();


        //DATE 
        // Définit le fuseau horaire par défaut à utiliser.
        date_default_timezone_set('UTC');

        $date = new DateTimeImmutable();

        // METTRE AU BON FORMAT
        $date->format('Y-m-d');


        // TRANSFORME LES ID EN OBJETS
        $candidatObject = $candidatRepository->find($candidatID);
        $jobOfferObject = $jobOfferRepository->find($jobOfferID);

        // SET CANDIDATURE AVEC LES OBJETS
        $candidature->setCandidat($candidatObject);
        $candidature->setJobOffer($jobOfferObject);
        $candidature->setDate($date);
        $candidature->setApproved(0);

        $entityManager->persist($candidature);
        $entityManager->flush();


        return $this->redirectToRoute('app_jobOffer');
    }

    #[Route('/{id}', name: 'app_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
    }
}
