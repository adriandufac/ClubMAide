<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AccueilFiltrageFormType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $filtreForm = $this->createForm(AccueilFiltrageFormType::class);
        $filtreForm->handleRequest($request);


        $this->denyAccessUnlessGranted('ROLE_USER');
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
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $villeForm = $this->createForm(VilleType::class,$ville);
        $villeForm->handleRequest($request);
        if($villeForm->isSubmitted()){
            if ($ville->getNom() != ""){
                $query = $entityManager->createQuery(
                    "SELECT v
            FROM App\Entity\Ville v
            WHERE v.nom LIKE :nom"
                )->setParameter('nom', '%'.$ville->getNom().'%');
                $villes =$query->getResult();
            }
            else{
                $villes = $villeRepository-> findAll();
            }
        }
        else {
            $villes = $villeRepository-> findAll();
        }
        return $this->render('main/gestionville.html.twig',["villes" => $villes,'villeForm' =>$villeForm->createView()]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('main/profil.html.twig', ["user" => $user ]);
    }

}
