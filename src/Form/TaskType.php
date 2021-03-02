<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Task;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la tâche', 'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Nom de la tâche',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description', 'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Description',
                ]
            ])
            ->add('dueAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date effective', 
                'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Date effective',
                ]
            ])
            ->add('tag', EntityType::class, ['class' => Tag::class, 'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('c')
                ->orderBy('c.name', 'ASC');
                },
                'choice_label'=> 'name',
                'label' => 'categorie',
                'attr' => [
                    'class' => 'form-control col-8', 'title' => 'Categorie'
                ]
            ])
        ->add('save', SubmitType::class, [
            
            'label' => 'Enregistrer', 
            'attr' => [
                'class' => 'form-control col-2', 'title' => 'Enregistrer',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
