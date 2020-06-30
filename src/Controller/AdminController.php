<?php

namespace App\Controller;

use App\Entity\BiblioUser;
use App\Form\ImportType;
use App\Repository\BiblioUserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/", name="admin_index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/import", name="admin_import")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param BiblioUserRepository $eleves
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function importUsers(
        Request $request,
        EntityManagerInterface $em,
        BiblioUserRepository $eleves,
        UserPasswordEncoderInterface $passwordEncoder
    )

    {
        // create form
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';

        // verify data after submission
        if ($form->isSubmitted() && $form->isValid()) {
            $elevesFile = $form->get('eleves')->getData();

            // verify extension format
            $ext = $elevesFile->getClientOriginalExtension();
            if ($ext != "csv") {
                $this->addFlash('danger', "Le fichier doit être de type .csv. 
                Format actuel envoyé : .$ext");

                return $this->redirectToRoute('admin_import');
            }

            // save file on server
            $originalFilename = pathinfo($elevesFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . ".csv";
            $elevesFile->move(
                $destination,
                $newFilename
            );

            // drop lines already in table
            $oldEleves = $eleves->findAll();
            foreach ($oldEleves as $line) {
                $em->remove($line);
            }

            // open the file to put data in DB
            $csv = fopen($destination . $newFilename, 'r');
            $i = 0;
            while (($data = fgetcsv($csv, 0, ';')) !== FALSE) {
                // pass the first title line
                if ($i != 0) {
                    // and add pupils
                    $eleve = new BiblioUser();
                    $dateNaissance = DateTime::createFromFormat("d/m/Y", $data[3]);
                    $username = $dateNaissance->format('Y').' '.$data[0].' '.$data[2];
                    $eleve
                        ->setUsername($username)
                        ->setRoles(["ROLE_USER"])
                        ->setPassword('123456')
                        ->setNom($data[0])
                        ->setPrenom($data[2])
                        ->setDateNaissance($dateNaissance)
                        ->setSexe($data[4])
                        ->setIne($data[5])
                        ->setSection($data[15])
                        ->setIsCaution(0)
                        ->setEmprunts(0)
                    ;
                    $em->persist($eleve);
                }
            $i++;
            }
            // send confirmations
            $this->addFlash(
                'success',
                "$i élèves correctement ajoutés"
            );
            $em->flush();
        }

        // find all lines in rateCards
        $eleves = $eleves->findAll();

        return $this->render('admin/import.html.twig', [
            'form' => $form->createView(),
            'eleves' => $eleves,
        ]);
    }
}
