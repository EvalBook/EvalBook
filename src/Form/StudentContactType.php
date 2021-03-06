<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

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
        $firstName = '';
        $lastName = '';
        $address = '';
        $phone = '';
        $email = '';
        $relation = '';
        $schoolReport = '';

        if(!is_null($options['contactRelation'])) {
            $firstName = $options['contactRelation']->getContact()->getFirstName();
            $lastName = $options['contactRelation']->getContact()->getLastName();
            $address = $options['contactRelation']->getContact()->getAddress();
            $phone = $options['contactRelation']->getContact()->getPhone();
            $email = $options['contactRelation']->getContact()->getEmail();
            $schoolReport = $options['contactRelation']->getSendSchoolReport();
            $relation = $options['contactRelation']->getRelation();
        }

        $builder
            // Classic contact object information.
            ->add('firstName', TextType::class, [
                'data' => $firstName,
            ])

            ->add('lastName', TextType::class, [
                'data' => $lastName,
            ])

            ->add('address', TextType::class, [
                'data' => $address,
            ])

            ->add('phone', TextType::class, [
                'data' => $phone,
                'constraints' => [
                    // Enable callback to check if start date is lower than period end date.
                    new Regex([
                        'message' => 'This phone number is invalid, it must match +32479111111 or +32 479 11 11 11 or +32479 11 11 11',
                        'pattern' => '/\+?[0-9]{2,3}\s?[0-9\s]{3,}/',
                    ]),
                ]
            ])

            ->add('email', EmailType::class, [
                'data' => $email,
            ])

            // Relation with current student.
            ->add('relation', ChoiceType::class, [
                'expanded' => false,
                'choices' => array_combine($options['relations'], $options['relations']),
                'data' => $relation,
            ])

            ->add('schoolReport', ChoiceType::class, [
                'expanded' => false,
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
                'data' => $schoolReport,
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'contactRelation' => null,
            'relations' => [],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'field.empty',
                ]),
            ],
        ]);
    }
}
