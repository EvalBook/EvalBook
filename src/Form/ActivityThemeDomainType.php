<?php

namespace App\Form;

use App\Entity\ActivityTheme;
use App\Entity\ActivityThemeDomain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ActivityThemeDomainType extends AbstractType
{
    /**
     * ActivityThemeDomain add/edit form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // Activity theme domain display name
            ->add('displayName', TextType::class, [
                'constraints' => new Length([
                    'max' => 100,
                    'min' => 2,
                    'maxMessage' => 'The display name must be between 2 and 100 chars',
                    'minMessage' => 'The display name must be between 2 and 100 chars',
                ]),
            ])

            // Global parent theme.
            ->add('activityTheme', EntityType::class, [
                'class' => ActivityTheme::class,
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * Default configuration.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivityThemeDomain::class,
            'required' => true,
        ]);
    }
}
