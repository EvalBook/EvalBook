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

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * Build the user add / edit form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // First name form input
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'user.first-name-too-short',
                        'maxMessage' => 'user.first-name-too-long',
                    ])
                ]
            ])

            // Last name form input.
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'user.last-name-too-short',
                        'maxMessage' => 'user.last-name-too-long',
                    ])
                ]
            ])

            // Email form input.
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'user.email-is-null',
                    ])
                ]
            ])

            // Password and password verify form inputs.
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'user.password-not-match',
                'required' => false,
                'empty_data' => '',
                'first_options'  => ['empty_data' => ''],
                'second_options' => ['empty_data' => ''],
            ])

            // User roles.
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_combine(
                    array_map(function($value){ return 'roles.' . $value; },
                              User::getAssignableRoles()),
                              User::getAssignableRoles()
                ),

                'group_by' => function($value) {
                    $role = str_replace('ROLE_', '', $value);
                    return strtolower(substr($role, 0, strpos($role, '_')));
                },
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'required' => true,
        ]);
    }
}
