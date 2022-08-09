<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulerSortieType;
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
    public function créer(EntityManagerInterface $entityManager, Request $request,EtatRepository  $etatRepository,UserRepository $userRepository, LieuRepository $lieuRepository){

        $sortie =new Sortie();
        $lieu = new Lieu();

        $sortieForm = $this->createForm(CreerUneSortieType::class,$sortie);
        $sortieForm->handleRequest($request);

        $lieuForm = $this->createForm(lieuType::class,$lieu);


        if($sortieForm->isSubmitted() && $lieuForm->isSubmitted()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Créée']));
            $sortie->setUserOrganisateur($userRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]));
            $sortie->setLieu($lieuRepository->find($lieu->getId()));
            $entityManager->persist($sortie);
            $entityManager->flush();
            // redirect page liste des sorties
            return $this->redirectToRoute('main_page');
        }
        return $this->render('sortie/ajoutersortie.html.twig',['sortieForm' =>$sortieForm->createView(),'lieuForm'=> $lieuForm->createView()]);
    }

    /**
     * @Route("/show/{id}", name="sortie_show")
     */
    public function show(Sortie $sortie, SortieRepository $sortieRepository, int $id){

        $sortie = ($sortieRepository->find($id));
        return $this->render('sortie/show.html.twig',['sortie' => $sortie]);

    }

    /**
     * @Route("/inscription/{id}/{id2}", name="inscription_sortie")
     */
    public function inscription(EntityManagerInterface $entityManager,SortieRepository $sortieRepository, UserRepository $userRepository,int $id, int $id2){
        $user = $userRepository->find($id2);
        $sortie = $sortieRepository->find($id);
        $user->addInscriptionsSorty($sortie);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('sortie_show',['id'=>$id]);
    }

    /**
     * @Route("/desinscription/{id}/{id2}", name="desinscription_sortie")
     */
    public function desinscription(EntityManagerInterface $entityManager,SortieRepository $sortieRepository, UserRepository $userRepository,int $id, int $id2){
        $user = $userRepository->find($id2);
        $sortie = $sortieRepository->find($id);
        $user->removeInscriptionsSorty($sortie);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('sortie_show',['id'=>$id]);
    }

    /**
     * @Route("/annulation/{id}", name="annulation_sortie")
     */
    public function annulation(EntityManagerInterface $entityManager,SortieRepository $sortieRepository, int $id, EtatRepository $etatRepository){
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat(($etatRepository->find(6)));
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('main_page');
    }

    /**
     * @Route("/annuler/{id}", name="annuler_sortie")
     */
    public function annulerSortie(SortieRepository $sortieRepository, int $id, Request $request, EntityManagerInterface $entityManager){
        $sortie = $sortieRepository->find($id);
        $sortie->setInfosSortie('');
        $form = $this->createForm(AnnulerSortieType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           // $sortie->setInfosSortie($form->getData());
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('annulation_sortie',['id'=>$id]);
        }

        return $this->render('sortie/annuler_sortie.html.twig',['sortie' => $sortie,
            'form' => $form->createView()]);
    }
}