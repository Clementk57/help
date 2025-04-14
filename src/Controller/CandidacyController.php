<?php

namespace App\Controller;

use App\Entity\Candidacy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/candidacies', name: 'api_candidacy')]
final class CandidacyController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$candidacies = $em->getRepository(Candidacy::class)->findAll();
		$data = $serializer->serialize($candidacies, 'json', ['groups' => 'candidacy:read']);

        return new JsonResponse($data, 200, [], true);
    }

	#[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Candidacy $candidacy, SerializerInterface $serializer): JsonResponse
    {
		$data = $serializer->serialize($candidacy, 'json', ['groups' => 'candidacy:read']);

        return new JsonResponse($data, 200, [], true);
    }

	#[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$candidacy = $serializer->deserialize($request->getContent(), Candidacy::class, 'json');
		$user = $this->getUser();
		$candidacy->setRcruiter($user);
		$em->persist($candidacy);
		$em->flush();

        return new JsonResponse(['massage' => 'candidacy created'], 201);
    }

	#[Route('/{id}', name: 'update', methods: ['PUT'])]
	public function update(Candidacy $candidacy, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$serializer->deserialize($request->getContent(), Candidacy::class, 'json', ['object_to_populate' => $candidacy]);
		$em->flush();

		return new JsonResponse(['message' => 'candidacy updated', 200]);
	}

	#[Route('/{id}', name: 'patch', methods: ['PATCH'])]
	public function patch(Candidacy $candidacy, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
		$serializer->deserialize($request->getContent(), Candidacy::class, 'json', ['object_to_populate' => $candidacy]);
		$em->flush();

		return new JsonResponse(['message' => 'candidacy updated', 200]);
	}

	#[Route('/{id}', name: 'delete', methods: ['DELETE'])]
	public function delete(Candidacy $candidacy, EntityManagerInterface $em): JsonResponse
    {
		$em->remove($candidacy);
		$em->flush();

		return new JsonResponse(['message' => 'candidacy deleted', 204]);
	}
}
