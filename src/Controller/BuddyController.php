<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BuddyController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche_buddy')]
    public function index(): Response
    {
        return $this->render('buddy/index.html.twig');
    }
}
