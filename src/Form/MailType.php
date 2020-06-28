<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', EmailType::class, [
                'data' => $options['from'],
                'disabled' => strlen($options['from']) > 0,
            ])

            ->add('to', EmailType::class, [
                'data' => $options['to'],
                'disabled' => strlen($options['to']) > 0,
            ])

            ->add('carbonCopy', TextType::class, [
                'required' => false,
                'constraints' => new Callback(function($object, ExecutionContextInterface $context) {
                    $carbon = $context->getRoot()->get('carbonCopy')->getData();
                    if(is_null($carbon)) {
                        return;
                    }
                    elseif(strpos($carbon, ',') !== -1) {
                        foreach (explode(',', $carbon) as $cc) {
                            if (!filter_var(trim($cc), FILTER_VALIDATE_EMAIL)) {
                                $context->buildViolation('A mail address in carbon copy is not valid')
                                        ->addViolation();
                            }
                        }
                    }
                    else {
                        if (!filter_var(trim($carbon), FILTER_VALIDATE_EMAIL)) {
                            $context->buildViolation('A mail address in carbon copy is not valid')
                                    ->addViolation();
                        }
                    }
                }),
            ])

            ->add('subject', TextType::class, [
                'constraints' => new NotBlank([
                   'message' => 'The mail subject can not be empty'
                ]),
            ])

            ->add('message', TextareaType::class, [
                'constraints' => new NotBlank([
                    'message' => 'The mail content can not be empty'
                ]),
                'attr' => [
                    'rows' => '10',
                ],
            ])

            ->add('attachement', FileType::class, [
                'required' => false,
                'constraints' => new Callback(function($object, ExecutionContextInterface $context) {
                    $file = $context->getRoot()->get('attachement')->getData();
                    if(is_null($file))
                        return;

                    if(in_array($file->getClientMimeType(), [
                        'application/javascript',
                        'application/octet-stream',
                        'application/octet-stream',
                        'application/x-msdownload',
                        'application/exe',
                        'application/x-exe',
                        'application/dos-exe',
                        'vms/exe',
                        'application/x-winexe',
                        'application/msdos-windows',
                        'application/x-msdos-program',
                    ])) {
                        $context->buildViolation('The file type is not allowed')
                                ->addViolation();
                    }
                }),
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'from' => '',
            'to' => '',
        ]);
    }
}
