<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Provider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,['attr'=>[
                'placeholder' => '0'
            ] ])
            ->add('prix',IntegerType::class,['attr'=>[
                'placeholder' => '0 DT'
            ] ])
            ->add('quantity',IntegerType::class,['attr'=>[
                'placeholder' => 0
            ] ])
            ->add('provider', EntityType::class, [
                // looks for choices from this entity
                'class' => Provider::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose an option',
            ])
            ->add('photo', FileType::class, [ 'label' => 'Photo de l\'article (png/jpg)', // unmapped means that this field is not associated to any entity property 
            'mapped' => false, 
            // make it optional so you don't have to re-upload the PDF file 
            // every time you edit the Product details 
            'required' => false, // unmapped fields can't define their validation using annotations 
            // in the associated entity, so you can use the PHP constraint classes 
            'constraints' => [ 
                new File([ 'maxSize' => '1024k', 
                'mimeTypes' => [ 'image/png', 'image/jpeg', ], 
                'mimeTypesMessage' => 'Please upload a valid picture format', 
                ]) 
            ], 
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
