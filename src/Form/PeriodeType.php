<?php

namespace App\Form;

use App\Entity\Periode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class PeriodeType
 * @package App\Form
 */
class PeriodeType extends AbstractType
{
    /**
     * Build a form for Period entity ( create new periods )
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // The period name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => 'period.name-too-short',
                        'maxMessage' => 'period.name-too-long'
                    ]),
                ],
            ])

            // The period start date.
            ->add('dateStart', DateType::class, [
                'years' => range(date('Y') - 1, date('Y') + 2)
            ])

            // The period end date.

            ->add('dateEnd', DateType::class, [
                'constraints' => [
                    // Enable callback to check if start date is lower than period end date.
                    new Callback(function($object, ExecutionContextInterface $context) {
                        // Getting the period as it was sent with form.
                        $periode = $context->getRoot()->getData();

                        if ($periode->getDateStart() > $periode->getDateEnd()) {
                            $context->buildViolation('The period start date must be lower then the end date')
                                    ->addViolation();
                        }
                    }),
                ],
                'years' => range(date('Y') - 1, date('Y') + 2)

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
            'data_class' => Periode::class,
        ]);
    }
}
