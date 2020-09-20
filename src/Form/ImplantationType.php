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

use App\Entity\Implantation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ImplantationType extends AbstractType
{
    /**
     * Build the implantation add Form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Implantation name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'implantation.name-too-short',
                        'maxMessage' => 'implantation.name-too-long'
                    ]),
                ]
            ])

            // Implantation address ( street name and number ).
            ->add('address', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'implantation.address-too-short',
                        'maxMessage' => 'implantation.address-too-long'
                    ]),
                ]
            ])

            // School implantation is attached to.
            ->add('school', EntityType::class, [
                'class' => 'App\Entity\School',
            ])

            // Provide a way to add the implantation logo for school report use.
            ->add('logo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => new Image([
                   'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/tiff',
                    ],
                    'mimeTypesMessage' => 'The image file type is incorrect, please choose a new one',
                ]),
            ])

            // Choose the implantation school report theme.
            ->add('schoolReportTheme', EntityType::class, [
                'placeholder' => 'Choose the implantation school report theme',
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }


    /**
     * Default options.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Implantation::class,
            'required' => true,
        ]);
    }
}