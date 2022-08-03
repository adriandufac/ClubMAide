<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     * @Route("/profil", name="update")
     */
    public function form(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $em, User $user = null): Response
    {
        
        if(!$user){
            $user = new User();
            $action = 'create';
        }else{
            $action = 'edit';
        }

        $form = $this->createForm(RegistrationFormType::class, $user, ['type' => $action]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('administrateur')->getData() == true){
                $user->setRoles(["ROLE_ADMIN"]);
            }else{
                $user->setRoles(["ROLE_USER"]);
            }

            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->persist($user);
            $em->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            return $this->redirectToRoute('update');
        }

        return $this->render('registration/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'action'=> $action
        ]);

















        $user = new User();



    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil(Request $request, EntityManagerInterface $em): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){


            
            $this->addFlash('success', 'Votre profil à bien été mise à jour');
            $em->persist($user);
            $em->flush();
        }
        
            return $this->render('main/profil.html.twig',[
                'profilForm' => $form->createView()
            ]);        
    }

}


