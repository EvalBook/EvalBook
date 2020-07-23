<?php

namespace App\Form;

use App\Entity\NoteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class NoteTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Note type name.
            ->add('name', TextType::class, [
                'constraints' => new Length([
                    'min' => 1,
                    'max' => 255,
                    'minMessage' => "noteType.name-too-short",
                    'maxMessage' => "noteType.name-too-long",
                ])
            ])

            // Note type description.
            ->add('description')

            // Minimum allowable note.
            ->add('minimum')

            // Note type intervals.
            ->add('intervals')

            // Maximum allowable note.
            ->add('maximum')

            // Note coefficient.
            ->add('coefficient')

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NoteType::class,
        ]);
    }
}
