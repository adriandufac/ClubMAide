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
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;  


class ProfilUpdateAdminType extends AbstractType
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
