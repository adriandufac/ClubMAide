<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function gestionville(VilleRepository  $villeRepository): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $villes = $villeRepository-> findAll();
        return $this->render('main/gestionville.html.twig',["villes" => $villes]);
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
