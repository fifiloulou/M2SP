<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecetteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Donnez un titre à votre recette"))
            ->add('preparation', TextareaType::class, $this->getConfiguration("Contenu", "Rédigez votre recette"))
            ->add('ingredient', TextareaType::class, $this->getConfiguration("Ingrédient", "Faites une liste des ingrédients"))
            ->add('imageFile', VichImageType::class, $this->getConfiguration("Image", "Choisissez une belle photo au format jpg pour votre recette"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
