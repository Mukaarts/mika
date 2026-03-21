<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function dashboard(NewsRepository $newsRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'articles' => $newsRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/news/create', name: 'app_admin_news_create')]
    public function createNews(Request $request, EntityManagerInterface $em): Response
    {
        $article = new News();
        $article->setPublishedAt(new \DateTimeImmutable());

        $form = $this->createForm(NewsType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Artikel ugeluecht.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/news_form.html.twig', [
            'form' => $form,
            'editing' => false,
        ]);
    }

    #[Route('/news/{id}/edit', name: 'app_admin_news_edit')]
    public function editNews(News $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NewsType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Artikel aktualiséiert.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/news_form.html.twig', [
            'form' => $form,
            'editing' => true,
            'article' => $article,
        ]);
    }

    #[Route('/news/{id}/delete', name: 'app_admin_news_delete', methods: ['POST'])]
    public function deleteNews(News $article, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-' . $article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', 'Artikel geläscht.');
        }

        return $this->redirectToRoute('app_admin');
    }
}
