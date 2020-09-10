<?php

namespace App\Form;

use App\Entity\BiblioBook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiblioBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Obligatoire'],
                'label' => 'Titre du livre',
            ])
            ->add('auteur', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Auteur',
                'required' => false,
            ])
            ->add('editeur', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Editeur',
                'required' => false,
            ])
            ->add('dewey', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Côte Dewey',
                'required' => false,
            ])
            ->add('prix', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Chiffres uniquement'],
                'label' => 'Prix',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BiblioBook::class,
        ]);
    }
}
