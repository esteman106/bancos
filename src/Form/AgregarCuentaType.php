<?php

namespace App\Form;

use App\Entity\Cuentas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgregarCuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname',TextType::class,['help' => 'Apodo para la identificación de la cuenta',])
            ->add('num_cuenta',NumberType::class,['help' => 'Numero de la nueva cuenta que se agrega',])
            ->add('Agregar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cuentas::class,
        ]);
    }
}
