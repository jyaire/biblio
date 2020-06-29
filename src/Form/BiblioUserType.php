<?php

namespace App\Form;

use App\Entity\BiblioUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BiblioUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('password')
            ->add('dateNaissance',DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' =>  [
                    'Féminin' => 'f',
                    'Masculin' => 'm',
                ],
                ])
            ->add('section')
            ->add('isCaution')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BiblioUser::class,
        ]);
    }
}
