<?php

namespace App\Form;

use App\Entity\DeliveryAdress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryAdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adress')
            ->add('code_postal')
            ->add('city')
            ->add('name')
            ->add('lastname')
            ->add('pays', ChoiceType::class, [
                'choices'  => [
                    'pays' => [
                        'France' => 'France',
                        'Belgique' => 'Belgique',
                        'Luxembourg' => 'Luxembourg',
                    ]
                ],
            ])
            ->add('phone')
            ->add('comp_adress')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryAdress::class,
        ]);
    }
}
