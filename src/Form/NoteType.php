<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    /**
     * Create a form to add notes to activities.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eleve', TextType::class,[
                'disabled' => true,
                'label' => false,
            ])

            // The global student note.
            ->add('note', TextType::class, [
                'label' => false,
                'required' => true,
            ])

            // The note comment.
            ->add('comment', TextType::class, [
                'label' => false,
                'required' => false,
            ])
        ;
    }

    /**
     * Default options.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
