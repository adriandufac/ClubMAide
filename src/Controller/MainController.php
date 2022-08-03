<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/gestion_ville", name="gestion_ville")
     */
    public function gestionville(VilleRepository  $villeRepository, Request $request): Response
    {
        $ville = new Ville();
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $villeForm = $this->createForm(VilleType::class,$ville);
        $villeForm->handleRequest($request);
        if($villeForm->isSubmitted()){
            if ($ville->getNom() != ""){
                $villes = $villeRepository -> findBy(['nom' => $ville->getNom()]);
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
