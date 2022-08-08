<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerUneSortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/créer", name="sortie_créer")
     */
    public function créer(EntityManagerInterface $entityManager, Request $request,EtatRepository  $etatRepository,UserRepository $userRepository){
        $sortie =new Sortie();
        $sortieForm = $this->createForm(CreerUneSortieType::class,$sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted()){

            $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Créée']));
            $sortie->setUserOrganisateur($userRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]));
            $entityManager->persist($sortie);
            $entityManager->flush();
            // redirect page liste des sorties
            return $this->redirectToRoute('main_page');
        }
        return $this->render('sortie/ajoutersortie.html.twig',['sortieForm' =>$sortieForm->createView()]);
    }

    /**
     * @Route("/show/{id}", name="sortie_show")
     */
    public function show(Sortie $sortie, SortieRepository $sortieRepository, int $id){

        $sortie = ($sortieRepository->find($id));
        return $this->render('sortie/show.html.twig',['sortie' => $sortie]);

    }
}