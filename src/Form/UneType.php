<?php

namespace App\Form;

use App\Entity\Une;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UneType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Donnez un titre à votre news"))
            ->add('url', TextType::class, $this->getConfiguration("Source", "Recopier ici l'url de la source. Ex: https://www.google.com"))
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Début de l\'événement'
            ])
            ->add('end', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Fin de l\'événement'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Une::class,
        ]);
    }
}
