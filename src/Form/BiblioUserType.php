<?php

namespace App\Form;

use App\Entity\BiblioSection;
use App\Entity\BiblioUser;
use App\Repository\BiblioSectionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiblioUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $section = $options['section'];
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Prénom',
            ])
            ->add('password', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Mot de passe',
            ])
            ->add('dateNaissance',DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de naissance',
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' =>  [
                    'Féminin' => 'F',
                    'Masculin' => 'M',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Sexe',
                ])
            ->add('section', EntityType::class, [
                'class' => BiblioSection::class,
                'choice_label' => 'name',
                'mapped' => false,
                'data' => $section,
                'attr' => ['class' => 'form-control'],
                'label' => 'Section',
            ])
            ->add('isCaution', CheckboxType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Caution ?',
            ])
            ->add('ine', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'N° INE',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['section'])
            ->setDefaults([
            'data_class' => BiblioUser::class,
        ]);
    }
}
