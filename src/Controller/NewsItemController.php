<?php

namespace App\Controller;

use App\Entity\NewsItem;
use App\Form\NewsItemType;
use App\Repository\NewsItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/news/item")
 * @IsGranted("ROLE_ADMIN")
 */
class NewsItemController extends AbstractController
{
    /**
     * @Route("/", name="news_item_index", methods={"GET"})
     */
    public function index(NewsItemRepository $newsItemRepository): Response
    {
        return $this->render('news_item/index.html.twig', [
            'news_items' => $newsItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="news_item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $newsItem = new NewsItem();
        $newsItem->setAuthor($user);

        $form = $this->createForm(NewsItemType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newsItem);
            $entityManager->flush();

            return $this->redirectToRoute('news_item_index');
        }

        return $this->render('news_item/new.html.twig', [
            'news_item' => $newsItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="news_item_show", methods={"GET"})
     */
    public function show(NewsItem $newsItem): Response
    {
        return $this->render('news_item/show.html.twig', [
            'news_item' => $newsItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="news_item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NewsItem $newsItem): Response
    {
        $form = $this->createForm(NewsItemType::class, $newsItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_item_index', [
                'id' => $newsItem->getId(),
            ]);
        }

        return $this->render('news_item/edit.html.twig', [
            'news_item' => $newsItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="news_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NewsItem $newsItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($newsItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_item_index');
    }
}
