<?php

namespace ManagerBundle\Form;

use ManagerBundle\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('publishing_year', DateType::class, array(
                    'widget' => 'choice',
                    'years' => range(date('Y'), 1500))
            )
            ->add('upload_date', DateType::class, array(
                    'widget' => 'choice',
                    'years' => range(date('Y'), 1500),
                    'data' => new \DateTime("now"))
            )
            ->add('status', ChoiceType::class, array(
                    'choices'  => array(
                        'free' => 'free',
                        'taken' => 'taken',
                        'reserved' => 'reserved'))
            )
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class,
        ));
    }

}