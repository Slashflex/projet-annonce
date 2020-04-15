<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                    'attr' => ['placeholder' => 'Titre de votre annonce']
                ])
            // ->add('slug')
            ->add('prix', MoneyType::class, [
                'attr' => ['placeholder' => 'Prix de votre annonce']
            ])
            ->add('introduction', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Introduction de votre annonce'
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Contenu de votre annonce'
                ]
            ])
            ->add('imageCouverture', UrlType::class, [
                'attr' => [
                    'placeholder' => "URL de l'image de votre annonce"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
