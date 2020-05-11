<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\User;
use App\Repository\ClasseRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class ClasseType extends AbstractType
{
    public $users = array();

    public function __construct(ClasseRepository $repository, UserRepository $userRepository)
    {
        $titulaires = array();

        // Fetching all olready owners.
        foreach($repository->findAll() as $classe) {
            if(!is_null($classe->getTitulaire())) {
                $titulaires[] = $classe->getTitulaire();
            }
        }

        foreach($userRepository->findAll() as $user) {
            if(!in_array($user, $titulaires)) {
                $this->users[] = $user;
            }
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // The class name.
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'classe.name-too-short',
                        'maxMessage' => 'classe.name-too-long',
                    ])
                ]
            ])

            // The class owner.
            ->add('titulaire', EntityType::class, [
                'class' => User::class,
                'choices' => $this->users,

            ])

            // The class implantation
            ->add('implantation')

            // Submit button.
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
