<?php

namespace App\Form;

use App\Entity\NoteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NoteTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form fields.
        $builder
            // Note type name.
            ->add('name', TextType::class, [
                'constraints' => new NotBlank([
                    'message' => "noteType.name-empty",
                ]),
            ])

            // Note type description.
            ->add('description', TextType::class, [
                'constraints' => new Length([
                    'min' => 1,
                    'max' => 255,
                    'minMessage' => "noteType.field-too-short",
                    'maxMessage' => "noteType.field-too-long",
                ])
            ])

            // Minimum allowable note.
            ->add('minimum', TextType::class, [
                'constraints' => new Length([
                    'min' => 1,
                    'max' => 10,
                    'minMessage' => "noteType.field-too-short",
                    'maxMessage' => "noteType.field-too-long",
                ])
            ])

            // Maximum allowable note.
            ->add('maximum', TextType::class, [
                'constraints' => new Length([
                    'min' => 1,
                    'max' => 10,
                    'minMessage' => "noteType.field-too-short",
                    'maxMessage' => "noteType.field-too-long",
                ])
            ])

            // Note type intervals.
            ->add('intervals', TextType::class, [
                'required' => false,
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

            // Submit button.
            ->add('submit', SubmitType::class)
        ;

        // Custom transformer for intervals translation.
        $builder->get('intervals')
            ->addModelTransformer(new CallbackTransformer(
                function($intervalsArray) {
                    // transform the array to a string
                    if(!is_null($intervalsArray)) {
                        return implode(',', $intervalsArray);
                    }
                    return '';
                },
                function($intervalsString) {
                    // transform the string back to an array
                    if(!is_null($intervalsString)) {
                        return explode(',', $intervalsString);
                    }
                    return [];
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NoteType::class,
            'required' => true,
        ]);
    }
}
