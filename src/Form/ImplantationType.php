<?php

namespace App\Form;

use App\Entity\Ecole;
use App\Entity\Implantation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                        'minMessage' => 'schools-implantations.message.implantation-name-too-short',
                        'maxMessage' => 'schools-implantations.message.implantation-name-too-long'
                    ]),
                ]
            ])

            // Implantation address ( street name and number ).
            ->add('address', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'schools-implantations.message.implantation-address-too-short',
                        'maxMessage' => 'schools-implantations.message.implantation-address-too-long'
                    ]),
                ]
            ])

            // Implantation zip code.
            ->add('zipCode', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'schools-implantations.message.zip-is-blank'
                    ]),
                ]
            ])

            // Implantation country.
            ->add('country', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'schools-implantations.message.country-name-too-short',
                        'maxMessage' => 'schools-implantations.message.country-name-too-long'
                    ]),
                ]
            ])

            // Is defaul implantation.
            ->add('defaultImplantation', ChoiceType::class, [
                'choices' => [
                    'common.yes' => true,
                    'common.no' => false
                ]
            ])

            // The school the implantation refers to.
            ->add('ecole', EntityType::class, [
                'class' => Ecole::class,
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Implantation::class,
            'translation_domain' => 'forms',
            'required' => true
        ]);
    }
}