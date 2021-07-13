<?php

namespace App\Controller\Admin;

use App\Entity\CommentRecette;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentRecetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommentRecette::class;
    }

    public function configureFields(string $pageName): iterable
    {
        IdField::new('id');
        return [
            AssociationField::new('article'),
            AssociationField::new('author'),
            DateTimeField::new('createdAt'),
            TextareaField::new('content'),
        ];
    }
}
