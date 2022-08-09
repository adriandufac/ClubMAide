<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSearchType;
use App\Form\ProfilUpdateFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function form(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $em, AuthenticationException $authException = null): Response
    {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);

        
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

            return $this->redirectToRoute('main_page');
        }

                return $this->render('registration/register.html.twig', [
                    'user' => $user,
                    'form' => $form->createView()
                ]);

    }

    /**
     * @Route("/profil/{id}", name="update_user")
     */
    public function formUpdate(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $em,UserRepository $userRepository, int $id ): Response
    {

        $userProfil = $userRepository->find($id);


        $form = $this->createForm(ProfilUpdateFormType::class, $userProfil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfil -> setPassword(
            $userPasswordHasher->hashPassword(
                $userProfil,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->persist($userProfil);
            $em->flush();

            // return $userAuthenticator->authenticateUser(
            //     $userProfil,
            //     $authenticator,
            //     $request
            // );
            
            return $this->redirectToRoute('main_page');
        }
        
        return $this->render('main/profil.html.twig', [
            'form' => $form->createView(),
            'profil' => $userProfil,
        ]);
        
    }

    /**
     * @Route("/delete_user/{id}", name="delete_user")
     */
    public function deleteUser(int $id,UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        {
            $entityManager->remove($userRepository->find($id));
            $entityManager->flush();
            return $this->redirectToRoute('gestion_user');
        }

    }


    /**
     * @Route("/gestion_user", name="gestion_user")
     */
    public function formGestionUser(Request $request, EntityManagerInterface $em,UserRepository $userRepository): Response
    {


        $user = new User();

        $userForm = $this->createForm(UserSearchType::class,$user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted()){
            if ($user->getNom() != ""){
                $user = $userRepository->findUserSearchbar($user->getNom());
            }
            else{
                $user = $userRepository-> findAll();
            }
        }
        else {
            $user = $userRepository-> findAll();
        }

        return $this->render('registration/user.html.twig',[
            'userForm' => $userForm->createView(),
            'users' => $user
        ]);
    }

}


