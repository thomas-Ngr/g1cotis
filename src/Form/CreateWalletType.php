<?php

namespace App\Form;

use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DispatchRecipientType;

class CreateWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class)
            ->add('address', TextType::class)
            //->add('last_done')
            //->add('time_interval') // should be radion buttons : daily, monthly, yearly.
            //->add('type')
            //->add('user')

            ->add('dispatchRecipients', CollectionType::class, [
                // each entry in the array will be an "DispatchRecipient" field
                'entry_type' => DispatchRecipientType::class,
                // these options are passed to each "email" type
                /*
                'entry_options' => [
                    'attr' => ['class' => 'email-box'],
                ],
                */
            ])

            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ])
        ;


        // allow to create 5 recipients. This should be changed to a JS form afterwards.
        // should be a configurable constant.
        /*
        for($i = 0; $i < 5; $i++) {
            // may use DispatchRecipientType
            $builder->add('recipient' . $i , TextType::class);
            $builder->add('percent' . $i , PercentType::class);
        }
        */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
