<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActiviteNotesType
 * Handle a collection of form type to manage students notes in a single page.
 *
 * @package App\Form
 */
class ActiviteNotesType extends AbstractType
{
    /**
     * Create a form for ActiviteNotesType.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('notes', CollectionType::class, [
                'entry_type' => NoteType::class,
            ])

            ->add('submit', SubmitType::class)

        ;
    }

    /**
     * Define default options.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}