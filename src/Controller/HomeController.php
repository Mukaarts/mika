<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    public function root(): RedirectResponse
    {
        return $this->redirectToRoute('app_home', [], 301);
    }

    #[Route('/lb', name: 'app_home', defaults: ['_locale' => 'lb'])]
    #[Route('/en', name: 'app_home_en', defaults: ['_locale' => 'en'])]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'latestNews' => $newsRepository->findLatest(3),
        ]);
    }
}
