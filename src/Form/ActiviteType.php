<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Periode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Available periods.
            ->add('periode')
            ->add('noteType')
            ->add('name')
            ->add('user')
            ->add('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
            'periodes' => array(),
        ]);
    }
}
