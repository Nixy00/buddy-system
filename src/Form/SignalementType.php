<?php

namespace App\Form;

use App\Entity\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motif', ChoiceType::class, [
                'label' => 'Pourquoi signalez-vous ce message ?',
                'choices' => [
                    'Contenu inapproprié / Offensant' => 'Contenu inapproprié',
                    'Harcèlement / Intimidation' => 'Harcèlement',
                    'Spam / Publicité' => 'Spam',
                    'Arnaque / Fraude' => 'Arnaque',
                    'Autre' => 'Autre',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'form-check'],
            ])
            ->add('autreMotif', TextareaType::class, [
                'label' => 'Précisez le motif',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Veuillez décrire le problème...',
                    'style' => 'display: none; resize: none;',
                ],
                'label_attr' => ['style' => 'display: none;'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }
}
