<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgotPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email', 'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Email',
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password', 'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Password',
                ]
            ])
            ->add('valid', SubmitType::class, [
            
                'label' => 'Valider', 
                'attr' => [
                    'class' => 'form-control col-2 btn btn-success mt-2', 'title' => 'Valider',
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
