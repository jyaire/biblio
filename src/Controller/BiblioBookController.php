<?php

namespace App\Controller;

use App\Entity\BiblioBook;
use App\Form\BiblioBookType;
use App\Repository\BiblioBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/biblio/book")
 */
class BiblioBookController extends AbstractController
{
    /**
     * @Route("/", name="biblio_book_index", methods={"GET"})
     */
    public function index(BiblioBookRepository $biblioBookRepository): Response
    {
        return $this->render('biblio_book/index.html.twig', [
            'biblio_books' => $biblioBookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="biblio_book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $biblioBook = new BiblioBook();
        $form = $this->createForm(BiblioBookType::class, $biblioBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($biblioBook);
            $entityManager->flush();

            return $this->redirectToRoute('biblio_book_index');
        }

        return $this->render('biblio_book/new.html.twig', [
            'biblio_book' => $biblioBook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biblio_book_show", methods={"GET"})
     */
    public function show(BiblioBook $biblioBook): Response
    {
        return $this->render('biblio_book/show.html.twig', [
            'biblio_book' => $biblioBook,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="biblio_book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BiblioBook $biblioBook): Response
    {
        $form = $this->createForm(BiblioBookType::class, $biblioBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('biblio_book_index');
        }

        return $this->render('biblio_book/edit.html.twig', [
            'biblio_book' => $biblioBook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biblio_book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BiblioBook $biblioBook): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblioBook->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($biblioBook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('biblio_book_index');
    }
}
