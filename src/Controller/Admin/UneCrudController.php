<?php

namespace App\Controller\Admin;

use App\Entity\Une;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Une::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            DateTimeField::new('date'),
            DateTimeField::new('start'),
            DateTimeField::new('end'),
            UrlField::new('url'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updateAt'),
            AssociationField::new('author')->autocomplete(),
        ];
    }
}
