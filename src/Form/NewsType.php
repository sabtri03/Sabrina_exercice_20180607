<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('image_url')
            ->add('publication_date', DateType::class)
            ->add('author', EntityType::class, ['class'=>Author::class,'choice_label'=>'name'])
            ->add('categories',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'expanded'     => true,
                    'multiple'     => true,
                    'by_reference' => false,
                    ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
