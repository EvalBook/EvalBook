<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserRoleType extends AbstractType
{
    /**
     * Role add / edit form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'label' => 'useradd.label.roles-choose',
                'required' => true,
                'attr' => ['class' => 'form-check'],
                'multiple' => true,
                'expanded' => true,
                'choices' => array_combine(User::getAssignableRoles(), User::getAssignableRoles()),
            ])

            // Submit button.
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
