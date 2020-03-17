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
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private $translator;

    /**
     * UserType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


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
                'label' => $this->translator->trans("Enter the user first name"),
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => $this->translator->trans("First name must have at least 3 characters"),
                        'maxMessage' => $this->translator->trans('First name should have a maximum of 100 characters'),
                    ])
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Last name form input.
            ->add('lastName', TextType::class, [
                'label' => $this->translator->trans("Enter the user last name"),
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => $this->translator->trans("Last name must have at least 3 characters"),
                        'maxMessage' => $this->translator->trans('Last name should have a maximum of 100 characters'),
                    ])
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Email form input.
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans("E-mail cannot be null"),
                    ])
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

                // Password and password verify form inputs.
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('The password fields must match.'),
                'options' => [
                    'attr' => ['class' => 'form-control']
                ],
                'required' => false,
                'empty_data' => '',
                'first_options'  => [
                    'label' => $this->translator->trans('Password'),
                    'empty_data' => '',
                ],
                'second_options' => [
                    'label' => $this->translator->trans('Repeat Password'),
                    'empty_data' => '',
                ],
            ])

            // Active form input.
            ->add('active', ChoiceType::class, [
                'label' => $this->translator->trans("Is the user active"),
                'choices' => [
                    $this->translator->trans("Yes") => true,
                    $this->translator->trans("No") => false,
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Edit use roles.
            ->add("roles", ButtonType::class, [
                'label' => $this->translator->trans("Edit user roles"),
                'attr' => ['class' => 'btn btn-primary'],
            ])

            // Go back to the users list.
            ->add('back', ButtonType::class, [
                'label' => $this->translator->trans("Go back"),
                'attr' => ['class' => 'btn btn-primary'],
            ])

            // Submit button.
            ->add($this->translator->trans("Send"), SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
