<?php

namespace App\Controller;

use App\Entity\BiblioUser;
use App\Form\BiblioUserType;
use App\Repository\BiblioUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/biblio/user")
 */
class BiblioUserController extends AbstractController
{
    /**
     * @Route("/", name="biblio_user_index", methods={"GET"})
     */
    public function index(BiblioUserRepository $biblioUserRepository): Response
    {
        return $this->render('biblio_user/index.html.twig', [
            'biblio_users' => $biblioUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="biblio_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $biblioUser = new BiblioUser();
        $form = $this->createForm(BiblioUserType::class, $biblioUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($biblioUser);
            $entityManager->flush();

            return $this->redirectToRoute('biblio_user_index');
        }

        return $this->render('biblio_user/new.html.twig', [
            'biblio_user' => $biblioUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biblio_user_show", methods={"GET"})
     */
    public function show(BiblioUser $biblioUser): Response
    {
        return $this->render('biblio_user/show.html.twig', [
            'biblio_user' => $biblioUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="biblio_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BiblioUser $biblioUser): Response
    {
        $form = $this->createForm(BiblioUserType::class, $biblioUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('biblio_user_index');
        }

        return $this->render('biblio_user/edit.html.twig', [
            'biblio_user' => $biblioUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="biblio_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BiblioUser $biblioUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblioUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($biblioUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('biblio_user_index');
    }
}
