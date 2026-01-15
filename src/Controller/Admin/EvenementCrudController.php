<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EvenementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evenement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextareaField::new('description')->hideOnIndex(),
            TextField::new('lieu'),
            DateTimeField::new('dateDebut', 'Date début'),
            DateTimeField::new('dateFin', 'Date fin'),
            IntegerField::new('capacite', 'Capacité'),

            // Pour affichage dans la liste
            TextField::new('statut')->onlyOnIndex(),

            // Pour le formulaire (création/édition)
            ChoiceField::new('statut')
                ->setChoices([
                    'Ouvert' => 'OUVERT',
                    'Complet' => 'COMPLET',
                    'Annulé' => 'ANNULE',
                    'Terminé' => 'TERMINE',
                    'En attente' => 'EN_ATTENTE',
                ])
                ->hideOnIndex(),
        ];
    }
}
