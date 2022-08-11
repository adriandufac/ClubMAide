<?php
namespace App\Controller;
use App\Entity\Ville;
use App\Form\VilleAddType;
use App\Form\VilleSearchType;
use App\Repository\VilleRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
    * @Route("/gestion_ville", name="gestion_ville")
    */
    public function gestionville(VilleRepository  $villeRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $ville = new Ville();
        $ville2 = new Ville();


        $searchForm = $this->createForm(VilleSearchType::class,$ville);
        $searchForm->handleRequest($request);

        $addForm = $this->createForm(VilleAddType::class,$ville2);
        $addForm->handleRequest($request);

        if($searchForm->isSubmitted()){
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

        if ($addForm->isSubmitted()){
            $entityManager->persist($ville2);
            $entityManager->flush();
            return $this->redirectToRoute('gestion_ville');
        }
        return $this->render('main/gestionville.html.twig',["villes" => $villes,'villeForm' =>$searchForm->createView(),'villeForm2'=>$addForm->createView()]);
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
}
