<?php

namespace App\Controller;

use App\Entity\BiblioEmprunt;
use App\Form\BiblioEmpruntType;
use App\Repository\BiblioEmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioEmpruntController extends AbstractController
{
    /**
     * @Route("/biblio/emprunt/", name="biblio_emprunt_index", methods={"GET"})
     * @param BiblioEmpruntRepository $biblioEmpruntRepository
     * @return Response
     */
    public function index(BiblioEmpruntRepository $biblioEmpruntRepository): Response
    {
        return $this->render('biblio_emprunt/index.html.twig', [
            'biblio_emprunts' => $biblioEmpruntRepository->findAll(),
        ]);
    }

    /**
     * @Route("/emprunt/", name="biblio_emprunt_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {

        $biblioEmprunt = new BiblioEmprunt();
        $form = $this->createForm(BiblioEmpruntType::class, $biblioEmprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $biblioEmprunt->setEleve($eleve);
            $entityManager->persist($biblioEmprunt);
            $entityManager->flush();

            return $this->redirectToRoute('biblio_emprunt_index');
        }

        return $this->render('biblio_emprunt/new.html.twig', [
            'biblio_emprunt' => $biblioEmprunt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/biblio/emprunt/{id}", name="biblio_emprunt_show", methods={"GET"})
     */
    public function show(BiblioEmprunt $biblioEmprunt): Response
    {
        return $this->render('biblio_emprunt/show.html.twig', [
            'biblio_emprunt' => $biblioEmprunt,
        ]);
    }

    /**
     * @Route("/biblio/emprunt/{id}/edit", name="biblio_emprunt_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BiblioEmprunt $biblioEmprunt): Response
    {
        $form = $this->createForm(BiblioEmpruntType::class, $biblioEmprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('biblio_emprunt_index');
        }

        return $this->render('biblio_emprunt/edit.html.twig', [
            'biblio_emprunt' => $biblioEmprunt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/biblio/emprunt/{id}", name="biblio_emprunt_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BiblioEmprunt $biblioEmprunt): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblioEmprunt->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($biblioEmprunt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('biblio_emprunt_index');
    }
}
