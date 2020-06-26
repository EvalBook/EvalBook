<?php

namespace App\Form;

use App\Entity\StudentContact;
use App\Entity\StudentContactRelation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StudentExistingContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contact', EntityType::class, [
                'class' => StudentContact::class,
            ])
            // Relation with current student.
            ->add('relation', ChoiceType::class, [
                'expanded' => false,
                'choices' => array_combine(
                    StudentContactRelation::getAvailableRelations(),
                    StudentContactRelation::getAvailableRelations()
                ),
            ])

            ->add('schoolReport', ChoiceType::class, [
                'expanded' => false,
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
            ])
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
