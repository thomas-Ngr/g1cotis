<?php

namespace App\Form;

use App\Entity\DispatchRecipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use NumberFormatter;

// needs further exploration :
// https://symfony.com/doc/current/form/create_custom_field_type.html#creating-form-types-created-from-scratch
class DispatchRecipientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // I could add a "label" field (a name for this recipient).
            ->add('address', TextType::class)
            ->add('percent', PercentType::class, [
                'scale'=> 2,
                'rounding_mode' => NumberFormatter::ROUND_HALFUP
            ])
            //->add('wallet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DispatchRecipient::class,
        ]);
    }
}
