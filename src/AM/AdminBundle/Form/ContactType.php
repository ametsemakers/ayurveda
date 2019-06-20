<?php

namespace AM\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array('placeholder' => 'Votre nom', 'value' => 'Alex'),
                'constraints' => array(
                    new NotBlank(array("message" => "Entrez votre nom")),
                )
            ))
            ->add('subject', TextType::class, array(
                'attr' => array('placeholder' => 'Objet', 'value' => 'Bonjour'),
                'constraints' => array(
                    new NotBlank(array("message" => "Entrez votre sujet")),
                )
            ))
            ->add('email', EmailType::class, array(
                'attr' => array('placeholder' => 'Votre adresse mail', 'value' => 'aa@aa.aa'),
                'constraints' => array(
                    new NotBlank(array("message" => "Entrez une adresse mail valide")),
                    new Email(array("message" => "Votre adresse mail n'est pas valide")),
                )
            ))
            ->add('content', TextareaType::class, array(
                'attr' => array('placeholder' => 'Votre message',
                'rows' => '8', 'value' => 'Message important'),
                'constraints' => array(
                    new NotBlank(array("message" => "Entrez votre message")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}