<?php

namespace App\Form;

use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('biographie', TextareaType::class, [
                'label' => 'Biographie',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('langues', ChoiceType::class, [
                'label' => 'Langues parlées',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Français' => 'Français',
                    'Anglais' => 'Anglais',
                    'Espagnol' => 'Espagnol',
                    'Allemand' => 'Allemand',
                    'Arabe' => 'Arabe',
                    'Italien' => 'Italien',
                ],
                'attr' => ['class' => 'language-checkboxes'],
                'label_attr' => ['class' => 'checkbox-inline'],
            ])
            ->add('disponibilites', ChoiceType::class, [
                'label' => 'Disponibilités',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Flexible (selon planning)' => 'Flexible (selon planning)',
                    'Soirées principalement' => 'Soirées principalement',
                    'Week-end : après-midi' => 'Week-end : après-midi',
                    'Semaine : 18h-22h' => 'Semaine : 18h-22h',
                    'En semaine : midi' => 'En semaine : midi',
                ],
                'attr' => ['class' => 'availability-checkboxes'],
                'label_attr' => ['class' => 'checkbox-inline'],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, WEBP)',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ])
        ;

        $builder->get('langues')
            ->addModelTransformer(new CallbackTransformer(
                function ($languesAsString): array {
                    if (null === $languesAsString) {
                        return [];
                    }
                    return explode(', ', $languesAsString);
                },
                function ($languesAsArray): ?string {
                    if (empty($languesAsArray)) {
                        return null;
                    }

                    return implode(', ', $languesAsArray);
                }
            ))
        ;

        $builder->get('disponibilites')
            ->addModelTransformer(new CallbackTransformer(
                function ($dispoAsString): array {
                    if (null === $dispoAsString) {
                        return [];
                    }

                    return explode(', ', $dispoAsString);
                },
                function ($dispoAsArray): ?string {
                    if (empty($dispoAsArray)) {
                        return null;
                    }

                    return implode(', ', $dispoAsArray);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
