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

namespace App\Entity;

use App\Repository\UserConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserConfigurationRepository::class)
 */
class UserConfiguration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showLogo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showHelp;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showTitle;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showSearch;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $useSchools;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $useContacts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $usePredefinedActivitiesValues;


    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userConfiguration", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * Return the configuration id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return true if user want to see logo.
     * @return bool|null
     */
    public function getShowLogo(): ?bool
    {
        return $this->showLogo || is_null($this->showLogo);
    }


    /**
     * Set to true so the user will see the logo.
     * @param bool|null $showLogo
     * @return $this
     */
    public function setShowLogo(?bool $showLogo): self
    {
        $this->showLogo = $showLogo;

        return $this;
    }


    /**
     * Return true if user want to see help buttons.
     * @return bool|null
     */
    public function getShowHelp(): ?bool
    {
        return $this->showHelp || is_null($this->showHelp);
    }


    /**
     * Set to true so the user will see the help buttons.
     * @param bool|null $showHelp
     * @return $this
     */
    public function setShowHelp(?bool $showHelp): self
    {
        $this->showHelp = $showHelp;

        return $this;
    }


    /**
     * Return true if user want to see pages title.
     * @return bool|null
     */
    public function getShowTitle(): ?bool
    {
        return $this->showTitle || is_null($this->showTitle);
    }


    /**
     * Set to true so the user will see the footer.
     * @param bool|null $showTitle
     * @return $this
     */
    public function setShowTitle(?bool $showTitle): self
    {
        $this->showTitle = $showTitle;

        return $this;
    }


    /**
     * Return true if user want to see search bar.
     * @return bool|null
     */
    public function getShowSearch(): ?bool
    {
        return $this->showSearch || is_null($this->showSearch);
    }


    /**
     * Set to true so the user will see search bar.
     * @param bool|null $showSearch
     * @return $this
     */
    public function setShowSearch(?bool $showSearch): self
    {
        $this->showSearch = $showSearch;

        return $this;
    }

    /**
     * Return true if user want to use schools feature.
     * @return bool|null
     */
    public function getUseSchools(): ?bool
    {
        return $this->useSchools || is_null($this->useSchools);
    }


    /**
     * Set to true so the user will use schools feature.
     * @param bool|null $useSchools
     * @return $this
     */
    public function setUseSchools(?bool $useSchools): self
    {
        $this->useSchools = $useSchools;

        return $this;
    }


    /**
     * Return true if user want to use contacts feature.
     * @return bool|null
     */
    public function getUseContacts(): ?bool
    {
        return $this->useContacts || is_null($this->useContacts);
    }


    /**
     * Set to true so the user will use contacts feature.
     * @param bool|null $useContacts
     * @return $this
     */
    public function setUseContacts(?bool $useContacts): self
    {
        $this->useContacts = $useContacts;

        return $this;
    }


    /**
     * Return the User attached to this configuration.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Set the User attached to this configuration.
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Return true if user want to use default activities domains and skills values.
     * @return bool|null
     */
    public function getUsePredefinedActivitiesValues(): ?bool
    {
        return $this->usePredefinedActivitiesValues || is_null($this->usePredefinedActivitiesValues);
    }


    /**
     * Set to true so the user will see and use predefined activities domains and skills.
     * @param bool|null $usePredefined
     * @return $this
     */
    public function setUsePredefinedActivitiesValues(?bool $usePredefined): self
    {
        $this->usePredefinedActivitiesValues = $usePredefined;

        return $this;
    }


    /**
     * Set the default configuration values.
     */
    public function setDefaults()
    {
        $this->setShowLogo(true);
        $this->setShowHelp(true);
        $this->setShowTitle(true);
        $this->setShowSearch(true);
        $this->setUseSchools(true);
        $this->setUseContacts(true);
        $this->setUsePredefinedActivitiesValues(true);
    }
}
