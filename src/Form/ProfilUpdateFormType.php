<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProfilUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options ): void
    {


        $builder
        ->add('pseudo')
        ->add('prenom')
        ->add('nom')
        ->add('telephone')
        ->add('email')
        ->add('campus')
        ->add('actif', CheckboxType::class,[
        'label' => 'Actif : ',
        'required' => false,
        ])

        ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Les mots de passe doivent correspondre.',
        'options' => ['attr' => ['class' => 'password-field']],
        'required' => true,
        'first_options'  => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Répétez le mot de passe'],
        'mapped' => false,
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
            new NotBlank([
                'message' => 'Veuillez rentrer un mot de passe',
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'Votre mot de passe dois contenir au minimum 6 caractères',
                'max' => 4096,
            ]),
        ],
        ])


        // if (in_array('ROLE_USER', $roles, true)){
            ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Répétez le mot de passe'],
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez rentrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe dois contenir au minimum 6 caractères',
                    'max' => 4096,
                ]),
            ],
            ])
            ->add('profilphoto', FileType::class,
                [ 'mapped' => false, // désactive le mappage avec le champ dans l'entité (qui attend une chaîne de caractère)
                    'label' => 'upload ta photo de profil ici',
                        'constraints' => [ new Image( ['mimeTypesMessage' => 'Image format not allowed !'])
                    ],'required' =>false
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
