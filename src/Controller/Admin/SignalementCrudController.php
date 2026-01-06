<?php

namespace App\Controller\Admin;

use App\Entity\Signalement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

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
            TextField::new('raison'),
            TextareaField::new('commentaire')->hideOnIndex(),
            ChoiceField::new('statut')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Validé' => 'valide',
                    'Rejeté' => 'rejete'
                ]),
            DateTimeField::new('dateCreation', 'Date création')->hideOnForm(),
        ];
    }
}
