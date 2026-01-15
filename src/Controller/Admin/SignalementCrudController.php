<?php

namespace App\Controller\Admin;

use App\Entity\Signalement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SignalementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Signalement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('motif'),
            TextareaField::new('commentaireModeration', 'Commentaire')->hideOnIndex(),

            // Pour affichage dans la liste
            TextField::new('statut')->onlyOnIndex(),

            // Pour le formulaire (création/édition)
            ChoiceField::new('statut')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Validé' => 'valide',
                    'Rejeté' => 'rejete',
                ])
                ->hideOnIndex(),
            DateTimeField::new('dateSignalement', 'Date signalement')->hideOnForm(),
        ];
    }
}
