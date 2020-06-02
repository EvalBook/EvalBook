<?php

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
     * @ORM\Column(type="boolean")
     */
    private $isGlobalConfig;

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
        return $this->showLogo;
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
     * Return true if the current configuration was made GLOBAL ( all system users )
     * @return bool|null
     */
    public function getIsGlobalConfig(): ?bool
    {
        return $this->isGlobalConfig;
    }


    /**
     * Set to true in order to make this configuration the default one.
     * @param bool $isGlobalConfig
     * @return $this
     */
    public function setIsGlobalConfig(bool $isGlobalConfig): self
    {
        $this->isGlobalConfig = $isGlobalConfig;

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
     * Set the default configuration values.
     */
    public function setDefaults()
    {
        $this->setShowLogo(true);
        $this->setIsGlobalConfig(false);

    }
}
