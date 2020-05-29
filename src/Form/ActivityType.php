<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Period;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ActivityType extends AbstractType
{

    /**
     * Build a form to add / edit activities.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Available periods.
            ->add('period', EntityType::class, [
                'class' => Period::class,
                'choices' => $options['periods'],
            ])

            // The note type.
            ->add('noteType')

            // Activity name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'activity.name-empty',
                    ])
                ]
            ])

            ->add('comment')
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
            'data_class' => Activity::class,
            'periods' => array(),
        ]);
    }
}
