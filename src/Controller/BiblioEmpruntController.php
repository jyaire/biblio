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
            ->add('book', IntegerType::class, [
                'attr'            => ['class' => 'form-control'],
            ])
            ->add('Emprunter', SubmitType::class, [
                'attr'            => ['class' => 'primary-button'],
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biblioEmprunt = new BiblioEmprunt();
            $biblioEmprunt->setEleve($user);
            $livre = $bookRepo->findOneBy(['id'=>$form->get('book')->getData()]);
            // si le livre n'existe pas, rediriger vers le formulaire
            if ($livre == null) {
                $this->addFlash('danger', "Livre inconnu");
                return $this->redirectToRoute('biblio_emprunt', [
                    'user'=> $user->getId(),
                ]);
            }
            // si le livre est déjà emprunté, rediriger vers le formulaire
            $indispo=0;
            foreach($livre->getBiblioEmprunts() as $emprunt) {
                if($emprunt->getIsEmprunt()==1) {
                    $indispo=1;
                }
            }
            if ($indispo==1) {
                $this->addFlash('danger', "Livre déjà emprunté");
                return $this->redirectToRoute('biblio_emprunt', [
                    'user'=> $user->getId(),
                ]);
            }
            $biblioEmprunt
                ->setLivre($livre)
                ->setIsEmprunt(1)
                ->setDateEmprunt(new \DateTime());
            $user->setEmprunts($user->getEmprunts()+1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
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

        // Nombre maximal de livres empruntables
        // TODO à mettre dans une option de l'admin
        $limit = 1;

        return $this->render('biblio_emprunt/emprunt.html.twig', [
            'eleves' => $eleves,
            'classes' => $classes,
            'section' => $section,
            'user' => $user,
            'limit' => $limit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/retour/", name="biblio_retour", methods={"GET","POST"})
     * @param Request $request
     * @param BiblioEmpruntRepository $empruntsRepo
     * @param BiblioBookRepository $bookRepo
     * @return Response
     */
    public function retour(Request $request, BiblioEmpruntRepository $empruntsRepo, BiblioBookRepository $bookRepo): Response
    {
        $form = $this->createFormBuilder()
            ->add('book', IntegerType::class, [
                'attr'            => ['class' => 'form-control'],
            ])
            ->add('Rendre', SubmitType::class, [
                'attr'            => ['class' => 'primary-button'],
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre = $bookRepo->findOneBy(['id'=>$form->get('book')->getData()]);
            // si le livre n'existe pas, rediriger vers le formulaire
            if ($livre == null) {
                $this->addFlash('danger', "Livre inconnu");
                return $this->redirectToRoute('biblio_retour');
            }
            $biblioEmprunt = $empruntsRepo->findOneBy([
                'livre'=>$livre,
                'isEmprunt'=>1,
                ]);

            // si le livre n'est pas emprunté, rediriger vers le formulaire
            if ($biblioEmprunt==null) {
                $this->addFlash('danger', "Ce livre n'est pas considéré comme emprunté");
                return $this->redirectToRoute('biblio_retour');
            }
            // on récupère l'usser qui a emprunté le livre
            $user = $biblioEmprunt->getEleve();
            // et on met à jour la BDD pour le retour
            $biblioEmprunt
                ->setIsEmprunt(0)
                ->setDateRetour(new \DateTime());
            $user->setEmprunts($user->getEmprunts()-1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($biblioEmprunt);
            $entityManager->flush();

            $prenom = $user->getPrenom();
            $this->addFlash('success', "$prenom, merci de ranger le livre à sa place !");
            return $this->redirectToRoute('home_index');
        }

        return $this->render('biblio_emprunt/retour.html.twig', [
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
