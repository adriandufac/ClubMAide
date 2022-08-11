<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Campus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options ): void
    {
        $builder
        ->add('pseudo')
        ->add('prenom')
        ->add('nom')
        ->add('telephone')
        ->add('email')
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            "label" => "mot de passe",
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            ])
        ->add('administrateur')
        ->add('actif', CheckboxType::class, [
            'mapped' => true,
            'attr' => array('checked'   => 'checked')
        ])
    
        ->add('campus', EntityType::class,['label'=>'Campus','class'=>Campus::class,'choice_label'=>'nom'])
        ->add('profilphoto', FileType::class,
            [   'required'=>false,
                'mapped' => false, // désactive le mappage avec le champ dans l'entité (qui attend une chaîne de caractère)
                'label' => 'upload ta photo de profil ici',
                'constraints' => [ new Image( ['mimeTypesMessage' => 'Image format not allowed !'])],
            ]);
    }

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'type' => 'edit',
        ]);
    }
}
