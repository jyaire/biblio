<?php

namespace App\Controller;

use App\Entity\BiblioEmprunt;
use App\Entity\BiblioUser;
use App\Form\BiblioEmpruntType;
use App\Repository\BiblioBookRepository;
use App\Repository\BiblioEmpruntRepository;
use App\Repository\BiblioUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/emprunt/{user}", name="biblio_emprunt", methods={"GET","POST"}, defaults={"user":null})
     * @param Request $request
     * @param BiblioUserRepository $elevesRepo
     * @param BiblioBookRepository $bookRepo
     * @param BiblioUser|null $user
     * @return Response
     */
    public function emprunt(Request $request,
                            BiblioUserRepository $elevesRepo,
                            BiblioBookRepository $bookRepo,
                            ?BiblioUser $user): Response
    {
        $form = $this->createFormBuilder()
            ->add('book', IntegerType::class)
            ->add('send', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biblioEmprunt = new BiblioEmprunt();
            $biblioEmprunt->setEleve($user);
            $livre = $bookRepo->findOneBy(['id'=>$form->get('book')->getData()]);
            if ($livre == null) {
                $this->addFlash('danger', "Livre inconnu");
                return $this->redirectToRoute('biblio_emprunt', [
                    'user'=> $user->getId(),
                ]);
            }
            $biblioEmprunt
                ->setLivre($livre)
                ->setIsEmprunt(1)
                ->setDateEmprunt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($biblioEmprunt);
            $entityManager->flush();

            $this->addFlash('success', "Tu peux garder ce livre 15 jours !");
            return $this->redirectToRoute('home_index');
        }

        // si une section est choisie, on affiche les élèves

        if(isset($_GET['section'])) {
            $section = $_GET['section'];
            $classes = $elevesRepo->findClassDistinct();
            $eleves = $elevesRepo->findBy(['section'=>$section]);
        }
        else{
            // sinon on cherche les sections de l'école
            $section = null;
            $classes = $elevesRepo->findClassDistinct();
            $eleves = null;
        }

        return $this->render('biblio_emprunt/emprunt.html.twig', [
            'eleves' => $eleves,
            'classes' => $classes,
            'section' => $section,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/retour/", name="biblio_retour", methods={"GET","POST"})
     * @param Request $request
     * @param BiblioUserRepository $eleves
     * @return Response
     */
    public function retour(Request $request, BiblioUserRepository $eleves): Response
    {
        $eleves = $eleves->findAll();
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
            'eleves' => $eleves,
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
