<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StudentContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Classic contact object information.
            ->add('firstName', TextType::class)

            ->add('lastName', TextType::class)

            ->add('address', TextType::class)

            ->add('phone', NumberType::class)

            ->add('email', EmailType::class)

            // Relation with current student.
            ->add('relation', ChoiceType::class, [
                'expanded' => false,
                'choices' => array_combine($options['relations'], $options['relations']),
            ])

            ->add('schoolReport', ChoiceType::class, [
                'expanded' => false,
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'relations' => [],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'field.empty',
                ]),
            ]
        ]);
    }
}
