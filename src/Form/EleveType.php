<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class EleveType extends AbstractType
{
    /**
     * Create a form to add / edit students.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Student first name.
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'user.first-name-too-short',
                        'maxMessage' => 'user.first-name-too-long',
                    ])
                ]
            ])

            // Student last name.
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'user.last-name-too-short',
                        'maxMessage' => 'user.last-name-too-long',
                    ])
                ]
            ])

            // Birthday.
            ->add('birthday', BirthdayType::class)

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
            'data_class' => Eleve::class,
        ]);
    }
}
