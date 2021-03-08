<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> 'Email', 
                'label_attr'=>['title'=>'Votre mot de passe'],
                'attr'=>[
                    'class'=>'form-control col-6',
                    'pattern'=>'^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$',
                    'title'=>'ex: jacky@free.fr'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => "Mot de passe",
                'label_attr' => [
                'title' => "Votre mot de passe."
                ],
                'attr' => [
                'class' => 'form-control col-6',
                'pattern' => "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",
                
                'title' => "Votre mot de passe doit contenir au minimum 8 carac-
                tères ou plus qui sont d'au moins un certain nombre, 
                et une majuscule et minuscule dans un ordre aléatoire.",
                'maxlength' => 255
                ]
                ],
                'second_options' => ['label' => " Confirmez le mot de passe",
                'label_attr' => [
                'title' => "Votre mot de passe."
                ],
                'attr' => [
                'class' => 'form-control col-6',
                'pattern' => "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",
                
                'title' => "Votre mot de passe doit contenir au minimum 8 carac-
                tères ou plus qui sont d'au moins un certain nombre,
                et une majuscule et minuscule dans un ordre aléatoire.",
                'maxlength' => 255
             
                ]
            ],
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
