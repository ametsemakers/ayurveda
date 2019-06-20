<?php

namespace AM\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',     TextType::class)
            ->add('author',    TextType::class)
            ->add('content',   TextareaType::class)
            ->add('published', CheckboxType::class, array('required' => false,
                'label' => 'Publier'))
            ->add('images',    CollectionType::class, [
                'entry_type'   => ImageType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => false
            ])
            ->add('categories', CollectionType::class, [
                'entry_type'   => CategoryType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ])
            ->add('position',  EntityType::class, [
                'class'   => 'AMAdminBundle:Position',
                'choice_label' => 'name' 
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AM\AdminBundle\Entity\Article'
        ]);
    }
}