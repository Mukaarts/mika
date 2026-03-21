<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/lb/contact', name: 'app_contact', defaults: ['_locale' => 'lb'])]
    #[Route('/en/contact', name: 'app_contact_en', defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
