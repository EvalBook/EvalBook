<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserRoleType;
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
    private $formService;

    /**
     * Manage user services.
     *
     * @param EntityManagerInterface $manager
     * @param FormFactoryInterface $formFactoryInterface
     * @param RequestStack $stack
     */
    public function __construct(FormService $formService, EntityManagerInterface $manager, FormFactoryInterface $formFactoryInterface, RequestStack $stack)
    {
        $this->entityManager = $manager;
        $this->formFactory = $formFactoryInterface;
        $this->request = $stack->getCurrentRequest();
        $this->formService = $formService;
    }


    /**
     * Add a new user with a form.
     *
     * @param array|null $roles
     * @return array
     */
    public function addForm(?array $roles) : array
    {
        $user = new User();
        $userForm = $this->formFactory->create(UserType::class, $user);

        try {
            $userForm->handleRequest($this->request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                if(is_null($roles)) {
                    $roles = [
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
                    ];
                }

                $user->setRoles($roles);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return [true, null];
            }
        } catch (\Exception $e) {
            return [false, $userForm->createView()];
        }
        
        return [null, $userForm->createView()];
    }


    /**
     * Return a user UNIQ edit named form, handle the request too.
     *
     * @param User $user
     * @return array
     */
    public function editForm(User $user) : array
    {
        return $this->formService->createForm('edit-user' . $user->getId(), UserType::class, $user);
    }


    /**
     * Return a user UNIQ edit roles named form, handle the request too.
     *
     * @param User $user
     * @return array
     */
    public function roleEditForm(User $user) : array
    {
        return $this->formService->createForm('edit-roles' . $user->getId(), UserRoleType::class, $user);
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

}