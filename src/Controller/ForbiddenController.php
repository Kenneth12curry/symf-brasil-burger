<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForbiddenController extends AbstractController
{
    #[Route('/forbidden', name: 'app_forbidden')]
    public function index(): Response
    {
        return $this->render('forbidden/index.html.twig', [
            'controller_name' => 'ForbiddenController',
        ]);
    }
}
