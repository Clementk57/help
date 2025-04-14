<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
final class ApiController extends AbstractController
{
    #[Route('/offers', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$offers = $em->getRepository(Offer::class)->findAll();
		$data = $serializer->serialize($offers, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }

	#[Route('/offers/{id}', name: 'show', methods: ['GET'])]
    public function show(Offer $offer, SerializerInterface $serializer): JsonResponse
    {
		$data = $serializer->serialize($offer, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }

	#[Route('/offers', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$offer = $serializer->deserialize($request->getContent(), Offer::class, 'json');
		$user = $this->getUser();
		$offer->setRcruiter($user);
		$em->persist($offer);
		$em->flush();

        return new JsonResponse(['massage' => 'Offer created'], 201);
    }

	#[Route('/offers/{id}', name: 'update', methods: ['PUT'])]
	public function update(Offer $offer, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$serializer->deserialize($request->getContent(), Offer::class, 'json', ['object_to_populate' => $offer]);
		$em->flush();

		return new JsonResponse(['message' => 'Offer updated', 200]);
	}

	#[Route('/offers/{id}', name: 'patch', methods: ['PATCH'])]
	public function patch(Offer $offer, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$serializer->deserialize($request->getContent(), Offer::class, 'json', ['object_to_populate' => $offer]);
		$em->flush();

		return new JsonResponse(['message' => 'Offer updated', 200]);
	}

	#[Route('/offers/{id}', name: 'delete', methods: ['DELETE'])]
	public function delete(Offer $offer, EntityManagerInterface $em): JsonResponse
    {
		$em->remove($offer);
		$em->flush();

		return new JsonResponse(['message' => 'Offer deleted', 204]);
	}
}
