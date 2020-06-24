<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class StudentContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Classic contact object information.
            ->add('firstName', TextType::class)

            ->add('lastName', TextType::class)

            ->add('address', TextType::class)

            ->add('phone', TextType::class, [
                'constraints' => [
                    // Enable callback to check if start date is lower than period end date.
                    new Regex([
                        'message' => 'This phone number is invalid, it must match +32479111111 or +32 479 11 11 11 or +32479 11 11 11',
                        'pattern' => '/\+?[0-9]{2,3}\s?[0-9\s]{3,}/',
                    ]),
                ]
            ])

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
