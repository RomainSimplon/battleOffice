<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\DeliveryAdress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('pays', ChoiceType::class, [
            'choices'  => [
                'pays' => [
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Luxembourg' => 'Luxembourg',
                ]
            ],
        ])
            ->add('name')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('adress')
            ->add('code_postal')
            ->add('city')
            ->add('comp_adress')
            ->add('deliveryAdress',DeliveryAdressType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
