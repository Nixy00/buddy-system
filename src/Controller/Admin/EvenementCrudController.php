<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
            ChoiceField::new('statut')
                ->setChoices([
                    'Ouvert' => 'ouvert',
                    'Complet' => 'complet',
                    'Annulé' => 'annule',
                    'Terminé' => 'termine'
                ]),
        ];
    }
}
