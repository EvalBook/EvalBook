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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showFooter;

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
     * Return true if user want to see footer.
     * @return bool|null
     */
    public function getShowFooter(): ?bool
    {
        return $this->showFooter || is_null($this->showFooter);
    }


    /**
     * Set to true so the user will see the footer.
     * @param bool|null $showFooter
     * @return $this
     */
    public function setShowFooter(?bool $showFooter): self
    {
        $this->showFooter = $showFooter;

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
        $this->setShowFooter(true);
        $this->setShowHelp(true);
        $this->setShowTitle(true);
        $this->setShowSearch(true);
        $this->setUseSchools(true);
    }
}
