<?php

namespace App\Form;

use App\Entity\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignalementTraiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 'EN_ATTENTE',
                    'En cours' => 'EN_COURS',
                    'Traité / Fermé' => 'TRAITE',
                    'Rejeté' => 'REJETE',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('commentaireModeration', TextareaType::class, [
                'label' => 'Réponse au signalement (visible par l\'utilisateur)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Expliquez ici les actions prises ou la raison de la décision...',
                    'style' => 'resize: none;',
                ],
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
