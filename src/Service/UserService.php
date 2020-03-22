<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserRoleType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Manage User services.
 */
class UserService
{
    private $entityManager;
    private $formFactory;
    private $request;
    
    /**
     * Manage user services.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManagerInterface $manager, FormFactoryInterface $formFactoryInterface, RequestStack $stack)
    {
        $this->entityManager = $manager;
        $this->formFactory = $formFactoryInterface;
        $this->request = $stack->getCurrentRequest();
    }


    /**
     * Add a new user with a form.
     *
     * @return array
     */
    public function addForm() : array
    {
        $user = new User();
        $userForm = $this->formFactory->create(UserType::class, $user);

        try {
            $userForm->handleRequest($this->request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                $user->setRoles([
                    'ROLE_CLASS_CREATE',
                    'ROLE_CLASS_EDIT',
                    'ROLE_CLASS_PARAMETERS',
                    'ROLE_CLASS_VIEW',
                    'ROLE_CLASS_ASSIGN_STUDENT',
                    'ROLE_ACTIVITIES_LIST',
                    'ROLE_ACTIVITY_CREATE',
                    'ROLE_ACTIVITY_EDIT',
                    'ROLE_ACTIVITY_DELETE',
                    'ROLE_NOTEBOOK_VIEW',
                    'ROLE_BULLETINS_LIST',
                    'ROLE_BULLETINS_PRINT',
                    'ROLE_BULLETIN_VALIDATE',
                    'ROLE_BULLETIN_ADD_COMMENT',
                    'ROLE_BULLETIN_STYLE_EDIT',
                ]);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return [true, null];
            }
        } catch (\Exception $e) {
            return [false, $userForm->createView()];
        }
        
        return [null, $userForm->createView()];
    }


    public function addAdmin()
    {
        // TODO 
    }


    /**
     * Return a user UNIQ edit named form, handle the request too.
     *
     * @return array
     */
    public function editForm(User $user) : array
    {
        return $this->createForm('edit-user' . $user->getId(), UserType::class, $user);
    }


    /**
     * Return a user UNIQ edit roles named form, handle the request too.
     *
     * @return array
     */
    public function roleEditForm(User $user) : array
    {
        return $this->createForm('edit-roles' . $user->getId(), UserRoleType::class, $user);
    }


    /**
     * Simply delete a user entity.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user) : bool 
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            return true;
        }
        catch(Exception\NotFoundHttpException $e) {}

        return false;
    }


    /**
     * Manage generic form.
     *
     * @param string $name
     * @param string $classname
     * @param Object $obj
     * @return array
     */
    private function createForm(string $name, string $classname, Object $obj) : array
    {
        // Roles edition form.
        $form = $this->formFactory->createNamed('edit-roles' . $name, $classname, $obj);

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