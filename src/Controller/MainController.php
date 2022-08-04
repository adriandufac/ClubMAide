<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AccueilFiltrageFormType;
use App\Form\CampusType;
use App\Form\VilleAddType;
use App\Form\VilleSearchType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index(AccueilFiltrageFormType $accueilFiltrageFormType, Request $request, SortieRepository $sortieRepository): Response
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
         * */
        $sorties = $sortieRepository->findAll();

        // Traitement de l'état
        foreach ($sorties as $sortie) {
            if ($sortie->getDateLimiteInscription() < new \DateTime() && $sortie->getDateHeureDebut() > new \DateTime()) {
                $sortieRepository->updateEtat($sortie->getId(), 3);
            } else if (new \DateTime() > $sortie->getDateHeureDebut() and new \DateTime() < $sortie->getDateHeureDebut()->modify('+' . $sortie->getDuree() . 'hours')) {
                $sortieRepository->updateEtat($sortie->getId(), 4);
                // Modification de la date en enlevant sa durée car ajouter lors du traitement modify() dans les paramètres du if()
                $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . 'hours');
            } else if (new \DateTime() > $sortie->getDateHeureDebut()->modify('+' . $sortie->getDuree() . 'hours')) {
                $sortieRepository->updateEtat($sortie->getId(), 5);
                $sortie->getDateHeureDebut()->modify('-' . $sortie->getDuree() . 'hours');
            } else if ($sortie->getDateLimiteInscription() > new \DateTime()) {
                $sortieRepository->updateEtat($sortie->getId(), 2);
            }
        }


        $filtreForm = $this->createForm(AccueilFiltrageFormType::class);
        $filtreForm->handleRequest($request);



        return $this->render('main/index.html.twig',
            ['filtreForm' => $filtreForm->createView(),
                'sorties' => $sortieRepository->findAll()]);
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
