<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\JobOffer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('candidat', EntityType::class, [
                'class' => Candidat::class,
            ])
            ->add('jobOffer', EntityType::class, [
                'class' => JobOffer::class,
                'choice_label' => 'jobTitle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
