<?php


namespace App\Service;


use App\Entity\User;
use App\Entity\UserConfiguration;
use App\Repository\UserConfigurationRepository;

class ConfigurationService
{
    private $configurationRepository;

    /**
     * ConfigurationService constructor.
     * @param UserConfigurationRepository $repository
     */
    public function __construct(UserConfigurationRepository $repository)
    {
        $this->configurationRepository = $repository;
    }


    /**
     * @param User $user
     * @return UserConfiguration
     */
    public function load(User $user)
    {
        $configuration = $user->getUserConfiguration();
        // if no user config exists, then check for a default admin configuration
        if(null === $configuration) {

            $configuration = $this->configurationRepository->findOneBy(["isGlobalConfig" => true]);
            if(null === $configuration) {
                // If configuration is still null, then create a new one containing defaults.
                $configuration = new UserConfiguration();
                $configuration->setDefaults();
            }
        }

        return $configuration;
    }
}