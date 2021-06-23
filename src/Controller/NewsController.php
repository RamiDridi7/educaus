<?php

namespace App\Controller;

use App\Entity\NewsFeed;
use App\Form\NewsFeedType;
use App\Repository\NewsFeedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news/feed")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", name="news_feed_index", methods={"GET"})
     */
    public function index(NewsFeedRepository $newsFeedRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news_feeds' => $newsFeedRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="news_feed_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $news = new NewsFeed();
        if ($request->isMethod('POST')) {
            $news->setTitle($request->get('title'));
            $news->setDate(new \DateTime($request->get('date')));
            $news->setDescription($request->get('description'));
            $news->setPhoto($request->get('photo'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('manage');
        }

        return $this->render('news/index.html.twig', [
            'news_feed' => $news
        ]);
    }

    /**
     * @Route("/{id}", name="news_feed_show", methods={"GET"})
     */
    public function show(NewsFeed $newsFeed): Response
    {
        return $this->render('news/show.html.twig', [
            'news_feed' => $newsFeed,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="news_feed_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NewsFeed $newsFeed): Response
    {
        $form = $this->createForm(NewsFeedType::class, $newsFeed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_feed_index');
        }

        return $this->render('news/edit.html.twig', [
            'news_feed' => $newsFeed,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="news_feed_delete", methods={"POST"})
     */
    public function delete(Request $request, NewsFeed $newsFeed): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsFeed->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($newsFeed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_index');
    }
}
