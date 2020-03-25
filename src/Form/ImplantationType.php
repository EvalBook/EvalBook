<?php

namespace App\Form;

use App\Entity\Ecole;
use App\Entity\Implantation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;


class ImplantationType extends AbstractType
{
    private $translator;

    /**
     * ImplantationType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
                'label' => $this->translator->trans('Enter the implantation name'),
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => $this->translator->trans('The name should at least have 4 characters'),
                        'maxMessage' => $this->translator->trans('The name should have a maximum of 100 characters')
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Implantation address ( street name and number ).
            ->add('address', TextType::class, [
                'label' => $this->translator->trans('Enter the implantation address'),
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => $this->translator->trans('The address should at least have 4 characters'),
                        'maxMessage' => $this->translator->trans('The address should have a maximum of 100 characters')
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Implantation zip code.
            ->add('zipCode', IntegerType::class, [
                'label' => $this->translator->trans('Enter the implantation zip code'),
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('Zip code cannot be blank')
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Implantation country.
            ->add('country', TextType::class, [
                'label' => $this->translator->trans('Enter the implantation country'),
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => $this->translator->trans('The country should at least have 4 characters'),
                        'maxMessage' => $this->translator->trans('The country should have a maximum of 100 characters')
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // Is defaul implantation.
            ->add('defaultImplantation', ChoiceType::class, [
                'label' => $this->translator->trans("Is this implantation the default school one ?"),
                'choices' => [
                    $this->translator->trans("Yes") => true,
                    $this->translator->trans("No") => false,
                ],
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])

            // The school the implantation refers to.
            ->add('ecole', EntityType::class, [
                'class' => Ecole::class,
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            // Submit button.
            ->add($this->translator->trans("Send"), SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
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
        ]);
    }
}
