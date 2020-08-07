<?php

/**
 * Copyleft (c) 2020 EvalBook
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the European Union Public Licence (EUPL V 1.2),
 * version 1.2 (or any later version).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * European Union Public Licence for more details.
 *
 * You should have received a copy of the European Union Public Licence
 * along with this program. If not, see
 * https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 **/

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
            // If configuration is still null, then create a new one containing defaults.
            $configuration = new UserConfiguration();
            $configuration->setDefaults();
        }

        return $configuration;
    }
}