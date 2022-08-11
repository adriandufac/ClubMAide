<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CSVType;
use App\Form\UserSearchType;
use App\Form\ProfilUpdateFormType;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use App\Form\ProfilUpdateAdminType;
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
            $file = $form->get('profilphoto')->getData();
            if ($file)
            {
                // On renomme le fichier, selon une convention propre au projet
                // Par exemple nom de l'entité + son id + extension soit 'entite-1.jpg'

                $newFilename = strtolower($user->getPseudo()).".".$file->guessExtension();
                $file->move($this->getParameter('upload_profilphoto_user_dir'), $newFilename);
                $user->setImage($newFilename);
            }
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


            return $this->redirectToRoute('gestion_user');
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
            $file = $form->get('profilphoto')->getData();
            if ($file)
            {
                // On renomme le fichier, selon une convention propre au projet
                // Par exemple nom de l'entité + son id + extension soit 'entite-1.jpg'

                $newFilename = strtolower($userProfil->getPseudo()).".".$file->guessExtension();
                $file->move($this->getParameter('upload_profilphoto_user_dir'), $newFilename);
                $userProfil->setImage($newFilename);
            }

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
     * @Route("/profil/admin/{id}", name="admin_update_user")
     */
    public function FormAdminUser(Request $request,EntityManagerInterface $em,UserRepository $userRepository, int $id): Response
    {
        $userProfil = $userRepository->find($id);


        $form = $this->createForm(ProfilUpdateAdminType::class, $userProfil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($userProfil);
            $em->flush();
            
            return $this->redirectToRoute('gestion_user');
        }
        
        return $this->render('main/profil-admin.html.twig', [
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
    public function formGestionUser(Request $request,UserRepository $userRepository): Response
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

    /**
     * @Route("/add-users", name="add_users")
     */
    function addUsersByCSV(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher,CampusRepository $campusRepo)
    {

        // Form creation ommited
        $form = $this->createForm(CSVType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('submitedFile')->getData();

            // Open the file
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                // Read and process the lines.
                // Skip the first line if the file includes a header
                while (($data = fgetcsv($handle)) !== false) {
                    // Do the processing: Map line to entity, validate if needed

                    // Assign fields
                        $user = new User();
                        $user->setPseudo($data[0]);
                        $user->setNom($data[1]);
                        $user->setPrenom($data[2]);
                        $user->setTelephone((int)$data[3]);
                        $user->setEmail($data[4]);
                        $user->setPassword($userPasswordHasher->hashPassword($user,$data[5]));
                        $user->setCampus($campusRepo->findOneBy(['nom'=>$data[6]]));
                        $user->setAdministrateur(false);
                        $user->setActif(true);
                        $user->setRoles(["ROLE_USER"]);
                        //return $this->render('registration/addUserFromCSV.html.twig',['form' => $form->createView(),'data'=>$data[0]]);
                        try{$em->persist($user);
                        $em->flush();}
                        catch (UniqueConstraintViolationException $e){
                            return $this->render('registration/addUserFromCSV.html.twig',['form' => $form->createView(),'warning'=>'Attention probleme de key unique a l\'ajout en base']);
                        }
                    }

                    //

                fclose($handle);

               return $this->redirectToRoute('gestion_user');

            }
        }
        return $this->render('registration/addUserFromCSV.html.twig',['form' => $form->createView()]);
    }

}


