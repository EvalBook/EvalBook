<?php

namespace App\Form;

use App\Entity\ActivityThemeDomainSkill;
use App\Entity\NoteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ActivityThemeDomainSkillType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * Activity theme domain skill form.
         */
        $builder
            // Skill name.
            ->add('name', TextType::class, [
                'constraints' => new Length([
                    'max' => 100,
                    'min' => 2,
                    'maxMessage' => 'The skill name must be between 2 and 100 chars',
                    'minMessage' => 'The skill name must be between 2 and 100 chars',
                ]),
            ])

            // Skill description.
            ->add('description', TextType::class, [
                'required' => false,
            ])

            // Associated note type used to generate the school report total.
            ->add('noteType', EntityType::class, [
                'class'   => NoteType::class,
                'choices' => $options['noteTypes'],
            ])

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivityThemeDomainSkill::class,
            'noteTypes' => array(),
        ]);
    }
}
