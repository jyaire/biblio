<?php

namespace App\Controller;

use App\Entity\BiblioBook;
use App\Form\BiblioBookType;
use App\Form\BiblioBookSearchType;
use App\Repository\BiblioBookRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioBookController extends AbstractController
{
    /**
     * @Route("/admin/book/", name="biblio_book_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param BiblioBookRepository $biblioBookRepository
     * @return Response
     */
    public function index(BiblioBookRepository $biblioBookRepository): Response
    {
        return $this->render('biblio_book/index.html.twig', [
            'biblio_books' => $biblioBookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/book/new", name="biblio_book_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $biblioBook = new BiblioBook();
        $form = $this->createForm(BiblioBookType::class, $biblioBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $biblioBook
                ->setDateAjout(new DateTime())
                ->setIsDispo(1);

            $entityManager->persist($biblioBook);
            $entityManager->flush();

            $idBook = $biblioBook->getId();
            $titleBook = $biblioBook->getTitre();
            $message = "Le livre \"$titleBook\" a été ajouté. Notez bien son numéro : $idBook";
            $this->addFlash('success', $message);

            return $this->redirectToRoute('biblio_book_index');
        }

        return $this->render('biblio_book/new.html.twig', [
            'biblio_book' => $biblioBook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/biblio/book/search", name="biblio_book_search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request, BiblioBookRepository $biblioBookRepository): Response
    {
        $biblioBook = new BiblioBook();
        $form = $this->createForm(BiblioBookSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idBook = $form->get('id')->getData();
            $biblioBook = $biblioBookRepository->findOneBy(['id' => $idBook]);

            if ($biblioBook == null) {
                $message = "Le livre n°$idBook n'est pas répertorié, vérifiez le numéro";
                $this->addFlash('danger', $message);

                return $this->render('biblio_book/search.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            
            
            return $this->redirectToRoute('biblio_book_show', [
                'id' => $idBook,
            ]);
        }

        return $this->render('biblio_book/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/biblio/book/{id}", name="biblio_book_show", methods={"GET"})
     * @param BiblioBook $biblioBook
     * @return Response
     */
    public function show(BiblioBook $biblioBook): Response
    {
        return $this->render('biblio_book/show.html.twig', [
            'biblio_book' => $biblioBook,
        ]);
    }

    /**
     * @Route("/biblio/book/{id}/edit", name="biblio_book_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param BiblioBook $biblioBook
     * @return Response
     */
    public function edit(Request $request, BiblioBook $biblioBook): Response
    {
        $form = $this->createForm(BiblioBookType::class, $biblioBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $message = "Modification réussie";
            $this->addFlash('success', $message);

            return $this->redirectToRoute('biblio_book_index');
        }

        return $this->render('biblio_book/edit.html.twig', [
            'biblio_book' => $biblioBook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/biblio/book/{id}", name="biblio_book_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param BiblioBook $biblioBook
     * @return Response
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
