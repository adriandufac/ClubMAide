<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AccueilFiltrageFormType;
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
            $sortie->getDateHeureDebut()->modify('-2 hours')->setTimeZone($timeZone);
            $sortie->getDateLimiteInscription()->modify('-2 hours')->setTimeZone($timeZone);
            if ($sortie->getDateLimiteInscription() >= new DateTime() && count($sortie->getUsersInscrits()) < $sortie->getNbInscriptionsMax()) {
                $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Ouverte']));

                $entityManager->persist($sortie);
                $entityManager->flush();
//                $sortieRepository->updateEtat($sortie->getId(), 2);
                $dump2 = 'etat2';
            }
            if ($sortie->getDateLimiteInscription() < new DateTime() || $sortie->getNbInscriptionsMax() == count($sortie->getUsersInscrits())) {
//                    $sortieRepository->updateEtat($sortie->getId(), 3);
                $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Clôturée']));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $dump3 = 'etat3';
            }
            if (new DateTime() < $sortie->getDateHeureDebut()->modify('+' .$sortie->getDuree(). ' hours')) {
                if(new DateTime() > $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours')){
//                    $sortieRepository->updateEtat($sortie->getId(), 4);
                    $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Activité en cours']));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $dump4 = 'etat4';
                }
                    // Modification de la date en enlevant sa durée car ajouter lors du traitement modify() dans les paramètres du if()
            } else{
                $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . ' hours');
            }
            if (new DateTime() > $sortie->getDateHeureDebut()->modify('+' .$sortie->getDuree(). ' hours')) {
                    $sortie->getDateHeureDebut()->modify('-' .$sortie->getDuree(). ' hours');
//                $sortieRepository->updateEtat($sortie->getId(), 5);
                $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Passée']));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $dump5 = 'etat5';
            } else{
                $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree(). ' hours');
            }
            if( new DateTime() > $sortie->getDateHeureDebut()->modify('+1 month')) {
//                $sortieRepository->updateEtat($sortie->getId(), 7);
                $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Archivée']));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $sortie->getDateHeureDebut()->modify('-1 month'); // par rapport au premier +1 mois L46
            }else{
                $sortie->getDateHeureDebut()->modify('-1 month'); // par rapport au premier +1 mois L46
            }
            $sortie->getDateHeureDebut()->modify('+2 hours')->setTimeZone($timeZone);
            $sortie->getDateLimiteInscription()->modify('+2 hours')->setTimeZone($timeZone);
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
     * @Route("/gestion_ville", name="gestion_ville")
     */
    public function gestionville(VilleRepository  $villeRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $ville2 = new Ville();
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $villeForm = $this->createForm(VilleSearchType::class,$ville);
        $villeForm->handleRequest($request);

        $villeForm2 = $this->createForm(VilleAddType::class,$ville2);
        $villeForm2->handleRequest($request);

        if($villeForm->isSubmitted()){
            if ($ville->getNom() != ""){
                $villes = $villeRepository->findVilleSearchbar($ville->getNom());
            }
            else{
                $villes = $villeRepository-> findAll();
            }
        }
        else {
            $villes = $villeRepository-> findAll();
        }

        if ($villeForm2->isSubmitted()){
            $entityManager->persist($ville2);
            $entityManager->flush();
            return $this->redirectToRoute('gestion_ville');
        }
        return $this->render('main/gestionville.html.twig',["villes" => $villes,'villeForm' =>$villeForm->createView(),'villeForm2'=>$villeForm2->createView()]);
    }

    /**
     * @Route("/ville_edit/{id}", name="ville_edit")
     */

    public function edit(Request $request,int $id,VilleRepository  $villeRepository,EntityManagerInterface $entityManager): Response
    {
        $ville =($villeRepository->find($id));

        $villeForm = $this->createForm(VilleSearchType::class,$ville);

        $villeForm->handleRequest($request);
        //si on submit le formulaire
        if($villeForm->isSubmitted()){
            //ajout de la produit en base

            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'ville modifié!');
            //on affiche la liste des produits
            return $this->redirectToRoute('gestion_ville');
        }

        //on envoit le formulaire a la page d'ajout de category
        return $this->render('main/ville_edit.html.twig',['villeForm' =>$villeForm->createView()]);
    }

    /**
     * @Route("/ville_delete/{id}", name="ville_delete")
     */

    public function delete(int $id,VilleRepository $villeRepository,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($villeRepository->find($id));
        $entityManager->flush();
        return $this->redirectToRoute('gestion_ville');
    }

    /**
     * @Route("/campus_gestion", name="campus_gestion")
     */

    public function gestioncampus(CampusRepository  $campusRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();

        $campusform = $this->createForm(CampusType::class,$campus);
        $campusform->handleRequest($request);

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
        return $this->render('main/gestioncampus.html.twig',["campus" => $campus,'campusform' =>$campusform->createView()]);
    }
}

