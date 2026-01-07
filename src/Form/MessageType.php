<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Écrivez votre message ici...',
                    'class' => 'form-control',
                    'rows' => 3,
                    'style' => 'resize: none;',
                ],
            ])
        ;
    }
}
