<?php


namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Translation\TranslatorInterface;


class EntityService
{
    protected $formService;
    protected $entityManager;
    protected $repository;
    protected $translator;

    /**
     * Manage school services.
     *
     * @param FormService $formService
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(FormService $formService, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->formService = $formService;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }


    /**
     * Set the target repository.
     * @param ServiceEntityRepository $repository
     * @return ServiceEntityRepository
     */
    public function setRepository(ServiceEntityRepository $repository)
    {
        $this->repository = $repository;
        return $this->repository;
    }


    /**
     * Return the attached entity manager.
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }


    /**
     * Return the EcoleRepository.
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * Translate a string.
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }


    /**
     * @param string $type
     * @param Object $entity
     * @param string $prefix
     * @return array
     */
    public function addForm(string $type, Object $entity, string $prefix)
    {
        $id = $entity->getId() ?? "0";
        return $this->formService->createForm($prefix . $id, $type, $entity);
    }


    /**
     * @param string $type
     * @param Object $entity
     * @param string $prefix
     * @return array
     */
    public function editForm(string $type, Object $entity, string $prefix) : array
    {
        return $this->addForm($type, $entity, $prefix);
    }

}