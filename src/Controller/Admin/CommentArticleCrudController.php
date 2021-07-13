<?php

namespace App\Controller\Admin;

use App\Entity\CommentArticle;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommentArticle::class;
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
