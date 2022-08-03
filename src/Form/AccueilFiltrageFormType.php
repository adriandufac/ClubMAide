<?php

namespace App\Form;

use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class AccueilFiltrageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('campus', EntityType::class, [
                    'class' => 'App\Entity\Campus',
                    'choice_label' => 'nom',
                    'required' => false,
            ])
            ->add('nom_sortie', SearchType::class, [
                'required' => false,
                'label' => 'Le nom de la sortie contient',
                'attr' => ['placeholder' => 'Rechercher'],
            ])
            ->add('date_debut', DateType::class, [
                'required' => false,
                'label' => 'Entre',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],

            ])
            ->add('date_fin', DateType::class, [
                'required' => false,
                'label' => 'Et',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('isOrganisateur', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties dont e suis l\'organisateur/trice',
            ])
            ->add('isInscrit', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je suis inscrit/e',
            ])
            ->add('isPasInscrit', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
