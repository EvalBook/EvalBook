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

use App\Entity\ActivityThemeDomain;
use App\Entity\ActivityThemeDomainSkill;
use App\Entity\Period;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ActivityType extends AbstractType
{

    /**
     * Build a form to add / edit activities.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form fields.
        $builder

            ->add('activityThemeDomains', EntityType::class, [
                // Used to filter skills with JS.
                'class' => ActivityThemeDomain::class,
                'choices' => $options['activity_theme_domains'],
                'mapped' => false,
                'required' => true,
                'group_by' => 'activityTheme',
                'placeholder' => 'Choose an activity domain to continue...',
                'translation_domain' => 'templates',
            ])

            ->add('activityThemeDomainSkill', EntityType::class, [
                'class' => ActivityThemeDomainSkill::class,
                'required' => true,

            ])

            // Available periods.
            ->add('period', EntityType::class, [
                'class' => Period::class,
                'choices' => $options['periods'],
                'required' => true,
            ])

            // The note type.
            ->add('noteType', EntityType::class, [
                'class' => \App\Entity\NoteType::class,
                'placeholder' => "Select an available note type...",
                'translation_domain' => 'templates',
            ])


            // Note coefficient.
            ->add('coefficient', IntegerType::class, [
                'empty_data' => 1,
                'data' => 1,
                // Add contraints, data >= 1 & data <= 10
                'constraints' => new Callback(function($object, ExecutionContextInterface $context) {
                    $coefficient = $context->getRoot()->get('coefficient')->getData();

                    if ($coefficient <= 0 || $coefficient > 10) {
                        $context
                            ->buildViolation('Coefficient should be between 1 and 10')
                            ->addViolation()
                        ;
                    }
                }),
            ])

            // Activity name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'activity.name-empty',
                    ])
                ],
                'required' => true,
            ])

            ->add('isInShoolReport', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],

                'translation_domain' => 'templates',
            ])


            // Activity description.
            ->add('comment', CKEditorType::class, [
                'required' => false,
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
            'data_class' => \App\Entity\Activity::class,
            'periods' => array(),
            'activity_theme_domains' => array(),
        ]);
    }
}
