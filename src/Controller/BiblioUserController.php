<?php

namespace App\Controller;

use App\Entity\BiblioUser;
use App\Form\BiblioUserType;
use App\Repository\BiblioSectionRepository;
use App\Repository\BiblioUserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BiblioUserController extends AbstractController
{
    /**
     * @Route("/admin/user/", name="biblio_user_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(BiblioUserRepository $biblioUserRepository): Response
    {
        return $this->render('biblio_user/index.html.twig', [
            'biblio_users' => $biblioUserRepository->findAllButAdultsButLeft(),
        ]);
    }

    /**
     * @Route("/admin/user/new", name="biblio_user_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $biblioUser = new BiblioUser();
        $section = null;
        $form = $this->createForm(BiblioUserType::class, $biblioUser, ['section' => $section]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $biblioUser->getDateNaissance()->format('Y').' '.$biblioUser->getNom().' '.$biblioUser->getPrenom();
            $biblioUser
                ->setPassword($passwordEncoder->encodePassword(
                    $biblioUser,
                    $form->get('password')->getData()
                )
            )
                ->setRoles(["ROLE_USER"])
                ->setUsername($username)
                ->setEmprunts(0);
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
     * @Route("/biblio/user/{id}", name="biblio_user_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param BiblioUser $biblioUser
     * @return Response
     */
    public function show(BiblioUser $biblioUser): Response
    {
        return $this->render('biblio_user/show.html.twig', [
            'biblio_user' => $biblioUser,
        ]);
    }

    /**
     * @Route("/biblio/user/{biblioUser}/edit", name="biblio_user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param BiblioUser $biblioUser
     * @param BiblioSectionRepository $br
     * @return Response
     */
    public function edit(Request $request, BiblioUser $biblioUser, BiblioSectionRepository $br): Response
    {
        $section = $br->findOneBy(['name'=>$biblioUser->getSection()]);
        $form = $this->createForm(BiblioUserType::class, $biblioUser, ['section' => $section]);
        $form->remove('password');
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
     * @Route("/biblio/user/{id}", name="biblio_user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param BiblioUser $biblioUser
     * @return Response
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
