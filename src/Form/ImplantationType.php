<?php

namespace App\Form;

use App\Entity\Implantation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

            // Implantation zip code.
            ->add('zipCode', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'implantation.zip-is-blank'
                    ]),
                ]
            ])

            // Implantation country.
            ->add('country', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'implantation.country-name-too-short',
                        'maxMessage' => 'implantation.country-name-too-long'
                    ]),
                ]
            ])

            ->add('school', EntityType::class, [
                'class' => 'App\Entity\School',
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;

        $builder->get('zipCode')
            ->addModelTransformer(new CallbackTransformer(
                function ($zipCode) {
                    return (int) $zipCode;
                },

                function ($zipCode) {
                    return (string) $zipCode;
                }
            ))
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