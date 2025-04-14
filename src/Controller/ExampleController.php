<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExampleController extends AbstractController
{
    #[Route('/api/example', name: 'app_example')]
    public function index(): Response
    {
		$message = 'Bonjour';
		$offers = [
			'Offre 1',
			'Offre 2'
		];

        return $this->render('example/index.html.twig', [
            'controller_name' => 'ExampleController',
			'message' => $message,
			'offers' => $offers
        ]);
    }
}
