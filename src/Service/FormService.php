<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FormService
{
    private $entityManager;
    private $formFactory;
    private $request;

    /**
     * Manage forms services.
     *
     * @param EntityManagerInterface $manager
     * @param FormFactoryInterface $formFactoryInterface
     * @param RequestStack $stack
     */
    public function __construct(EntityManagerInterface $manager, FormFactoryInterface $formFactoryInterface, RequestStack $stack)
    {
        $this->entityManager = $manager;
        $this->formFactory = $formFactoryInterface;
        $this->request = $stack->getCurrentRequest();
    }


    /**
     * Manage generic form.
     *
     * @param string $name
     * @param string $classname
     * @param Object $obj
     * @return array
     */
    public function createForm(string $name, string $classname, Object $obj) : array
    {
        // Roles edition form.
        $form = $this->formFactory->createNamed($name, $classname, $obj);

        try {
            $form->handleRequest($this->request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($obj);
                $this->entityManager->flush();
                return [true, null];
            }
        }
        catch (\Exception $e) {
            return [false, $form->createView()];
        }

        return [null, $form->createView()];
    }
}