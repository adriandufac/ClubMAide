<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AccueilFiltrageFormType;
use App\Form\CampusModifType;
use App\Form\CampusType;
use App\Form\VilleAddType;
use App\Form\VilleSearchType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     * @throws \Exception
     */
    public function index(ManagerRegistry $doctrine,AccueilFiltrageFormType $accueilFiltrageFormType, Request $request, SortieRepository $sortieRepository,EtatRepository  $etatRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /*
         * ID DES DIFFERENTS ETAT
         * 1 - Créée
         * 2 - Ouverte
         * 3 - Clôturée
         * 4 - Activité en cours
         * 5 - Passée
         * 6 - Annulée
         * 7 - Archivée
         * */
        $sorties = $sortieRepository->findAll();
        $dump2 = '0';
        $dump3 = '0';
        $dump4 = '0';
        $dump5 = '0';
        $timeZone = new DateTimeZone('Europe/Paris');
        // Traitement de l'état
        $entityManager = $doctrine->getManager();

        foreach ($sorties as $sortie) {
            if($sortie->getEtat()->getId() != 6) {
                $sortie->getDateHeureDebut()->modify('-2 hours')->setTimeZone($timeZone);
                $sortie->getDateLimiteInscription()->modify('-2 hours')->setTimeZone($timeZone);
                if ($sortie->getDateLimiteInscription() >= new DateTime() && count($sortie->getUsersInscrits()) < $sortie->getNbInscriptionsMax()) {
                    $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Ouverte']));

                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    //                $sortieRepository->updateEtat($sortie->getId(), 2);
                    $dump2 = 'etat2';
                }
                if ($sortie->getDateLimiteInscription() < new DateTime() || $sortie->getNbInscriptionsMax() == count($sortie->getUsersInscrits())) {
                    //                    $sortieRepository->updateEtat($sortie->getId(), 3);
                    $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Clôturée']));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $dump3 = 'etat3';
                }
                if (new DateTime() < $sortie->getDateHeureDebut()->modify('+' . $sortie->getDuree() . ' hours')) {
                    if (new DateTime() > $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours')) {
                        //                    $sortieRepository->updateEtat($sortie->getId(), 4);
                        $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Activité en cours']));
                        $entityManager->persist($sortie);
                        $entityManager->flush();
                        $dump4 = 'etat4';
                    }
                    // Modification de la date en enlevant sa durée car ajouter lors du traitement modify() dans les paramètres du if()
                } else {
                    $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours');
                }
                if (new DateTime() > $sortie->getDateHeureDebut()->modify('+' . $sortie->getDuree() . ' hours')) {
                    $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours');
                    //                $sortieRepository->updateEtat($sortie->getId(), 5);
                    $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Passée']));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $dump5 = 'etat5';
                } else {
                    $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours');
                }
                if (new DateTime() > $sortie->getDateHeureDebut()->modify('+1 month')) {
                    //                $sortieRepository->updateEtat($sortie->getId(), 7);
                    $sortie->setEtat($etatRepository->findOneBy(['libelle' => 'Archivée']));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $sortie->getDateHeureDebut()->modify('-1 month'); // par rapport au premier +1 mois L46
                } else {
                    $sortie->getDateHeureDebut()->modify('-1 month'); // par rapport au premier +1 mois L46
                }
                $sortie->getDateHeureDebut()->modify('+2 hours')->setTimeZone($timeZone);
                $sortie->getDateLimiteInscription()->modify('+2 hours')->setTimeZone($timeZone);
            }
        }

        $filtreForm = $this->createForm(AccueilFiltrageFormType::class);
        $filtreForm->handleRequest($request);
        $sorties2 =$sortieRepository->findNonArchivees();
        return $this->render('main/index.html.twig',
            ['filtreForm' => $filtreForm->createView(),
                'sorties' => $sorties2,
                'dump2' => $dump2,
                'dump3' => $dump3,
                'dump4' => $dump4,
                'dump5' => $dump5]);
    }



    /**
     * @Route("/campus_gestion", name="campus_gestion")
     */

    public function gestioncampus(CampusRepository  $campusRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campus2 = new Campus();

        $campusform = $this->createForm(CampusModifType::class,$campus);
        $campusform->handleRequest($request);

        $campusform2 = $this->createForm(CampusType::class,$campus2);
        $campusform2->handleRequest($request);

        if($campusform->isSubmitted()){
            if ($campus->getNom() != ""){
                $campus = $campusRepository->findCampusSearchbar($campus->getNom());
            }
            else{
                $campus = $campusRepository-> findAll();
            }
        }
        else {
            $campus = $campusRepository-> findAll();
        }

        if ($campusform2->isSubmitted()){
            $entityManager->persist($campus2);
            $entityManager->flush();
            return $this->redirectToRoute('campus_gestion');
        }

        return $this->render('main/gestioncampus.html.twig',["campus" => $campus,'campusform' =>$campusform->createView(), 'campusform2' =>$campusform2->createView()]);
    }

    /**
     * @Route("/campus_edit/{id}", name="campus_edit")
     */

    public function editcampus(Request $request,int $id,CampusRepository  $campusRepository,EntityManagerInterface $entityManager): Response
    {
        $Campus =($campusRepository->find($id));

        $CampusForm = $this->createForm(CampusModifType::class,$Campus);

        $CampusForm->handleRequest($request);
        //si on submit le formulaire
        if($CampusForm->isSubmitted()){
            //ajout du campus en base

            $entityManager->persist($Campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus modifié!');
            //on affiche la liste des campus
            return $this->redirectToRoute('campus_gestion');
        }

        //on envoit le formulaire a la page d'ajout de category
        return $this->render('main/campus_edit.html.twig',['campusform' =>$CampusForm->createView()]);
    }

    /**
     * @Route("/campus_delete/{id}", name="campus_delete")
     */

    public function deletecampus(int $id,CampusRepository $campusRepository,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($campusRepository->find($id));
        $entityManager->flush();
        return $this->redirectToRoute('campus_gestion');
    }

    /**
     * @Route("/dash_board", name="dash_board")
     */
    public function dashBoard(): Response
    {
        return $this->render('nav/dash.html.twig', []);
    }

}

