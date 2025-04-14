<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Entity\Offer;
use App\Form\CandidacyType;
use App\Form\OfferType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{
    #[Route('/dashboard', name: 'app_offers')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $offers = $entityManager->getRepository(Offer::class)->findAll();
        return $this->render('app/dashboard.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/add-offer', name:'app_add_offer')]
    public function addOffer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $offer->setRecruiter($this->getUser());
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offers');
        }

		return $this->render('app/addOffer.html.twig', [
			'form' => $form->createView(),
		]);
    }

	#[Route('edit-offer/{id}', name:'app_edit_offer')]
	public function editOffer(EntityManagerInterface $entityManager, Request $request, string $id): Response
	{
		$offer = $entityManager->getRepository(Offer::class)->find($id);

		$form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $offer->setRecruiter($this->getUser());
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offers');
        }

		return $this->render('app/editOffer.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('delete-offer/{id}', name:'app_delete_offer')]
	public function deleteOffer(EntityManagerInterface $entityManager, string $id): Response
	{
		$offer = $entityManager->getRepository(Offer::class)->find($id);
		$entityManager->remove($offer);
		$entityManager->flush();

		return $this->redirectToRoute('app_offers');
	}

	#[Route('offer/{id}', name:'app_offer_detail')]
    public function offerDetail(EntityManagerInterface $entityManager, int $id): Response
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        return $this->render('app/offerDetail.html.twig', [
            'offer' => $offer,
        ]);
    }

	#[Route('/candidacies', name: 'app_candidacies')]
    public function candidacies(EntityManagerInterface $entityManager): Response
    {
        $candidacies = $entityManager->getRepository(Candidacy::class)->findAll();
        return $this->render('app/candidacies.html.twig', [
            'candidacies' => $candidacies,
        ]);
    }

	#[Route('candidacy/{id}', name:'app_candidacy_detail')]
    public function candidacyDetail(EntityManagerInterface $entityManager, int $id): Response
    {
        $candidacy = $entityManager->getRepository(Candidacy::class)->find($id);

        if (!$candidacy) {
            throw $this->createNotFoundException('Candidacy not found');
        }

        return $this->render('app/candidacyDetail.html.twig', [
            'candidacy' => $candidacy,
        ]);
    }

	#[Route('/add-candidacy/{id}', name:'app_add_candidacy')]
    public function addCandidacy(EntityManagerInterface $entityManager, Request $request, string $id): Response
    {
        $candidacy = new Candidacy();

        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
			$offer = $entityManager->getRepository(Offer::class)->find($id);
			$candidacy->setOffer($offer);
			$candidacy->addCandidate($this->getUser());
            $entityManager->persist($candidacy);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidacies');
        }

		return $this->render('app/addCandidacy.html.twig', [
			'form' => $form->createView(),
		]);
    }

	#[Route('edit-candidacy/{id}', name:'app_edit_candidacy')]
	public function editCandidacy(EntityManagerInterface $entityManager, Request $request, string $id): Response
	{
		$candidacy = $entityManager->getRepository(Candidacy::class)->find($id);

		$form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidacy);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidacies');
        }

		return $this->render('app/editCandidacy.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('delete-candidacy/{id}', name:'app_delete_candidacy')]
	public function deleteCandidacy(EntityManagerInterface $entityManager, string $id): Response
	{
		$candidacy = $entityManager->getRepository(Candidacy::class)->find($id);
		$entityManager->remove($candidacy);
		$entityManager->flush();

		return $this->redirectToRoute('app_candidacies');
	}
}