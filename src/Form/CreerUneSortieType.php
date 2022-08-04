<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Time;

class CreerUneSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
            ])
            ->add('duree', Time::class)
            ->add('dateLimiteInscription', DateTimeType::class, [
                'html5' => true,
            ])
            ->add('nbInscriptionsMax')
            ->add('infosSortie', TextareaType::class)
            ->add('campus')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Créée' => 'Créée',
                    'Ouverte' => 'Ouverte',
                    'Clôturée' => 'Clôturée',
                    'Activité en cours' => 'Activité en cours',
                    'Passée' => 'Passée',
                    'Annulée' => 'Annulée'
                ]
            ])
            ->add('lieu')
            ->add('usersInscrits')
            ->add('userOrganisateur')
            ->add('nom', VilleAddType::class)
            ->add('code_postal', VilleAddType::class)
            //Todo longitude et latitude
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
