<?php

namespace App\Entity;

use App\Repository\ActivityThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityThemeRepository::class)
 */
class ActivityTheme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNumericNotes;

    /**
     * @ORM\OneToMany(targetEntity=ActivityThemeDomain::class, mappedBy="activityTheme", orphanRemoval=true)
     */
    private $activityThemeDomains;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;


    /**
     * ActivityTheme constructor.
     */
    public function __construct()
    {
        $this->activityThemeDomains = new ArrayCollection();
    }


    /**
     * Return the activity theme id
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the activity theme name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the activity theme name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the activity theme school report display weight.
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }


    /**
     * Set the activity theme school report display weight.
     * @param int $weight
     * @return $this
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }


    /**
     * Return true if this activity theme must use numeric notes types.
     * @return bool|null
     */
    public function getIsNumericNotes(): ?bool
    {
        return $this->isNumericNotes;
    }


    /**
     * @param bool $isNumericNotes
     * @return $this
     */
    public function setIsNumericNotes(bool $isNumericNotes): self
    {
        $this->isNumericNotes = $isNumericNotes;

        return $this;
    }


    /**
     * @return Collection|ActivityThemeDomain[]
     */
    public function getActivityThemeDomains(): Collection
    {
        return $this->activityThemeDomains;
    }


    /**
     * Add an activity theme domain.
     * @param ActivityThemeDomain $activityThemeDomain
     * @return $this
     */
    public function addActivityThemeDomains(ActivityThemeDomain $activityThemeDomain): self
    {
        if (!$this->activityThemeDomains->contains($activityThemeDomain)) {
            $this->activityThemeDomains[] = $activityThemeDomain;
            $activityThemeDomain->setActivityTheme($this);
        }

        return $this;
    }


    /**
     * Remove an activity theme domain.
     * @param ActivityThemeDomain $activityThemeDomain
     * @return $this
     */
    public function removeActivityThemeDomain(ActivityThemeDomain $activityThemeDomain): self
    {
        if ($this->activityThemeDomains->contains($activityThemeDomain)) {
            $this->activityThemeDomains->removeElement($activityThemeDomain);
            // set the owning side to null (unless already changed)
            if ($activityThemeDomain->getActivityTheme() === $this) {
                $activityThemeDomain->setActivityTheme(null);
            }
        }

        return $this;
    }


    /**
     * Return the Activity Theme display name.
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }


    /**
     * Set the activity theme display name.
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }


    /**
     * String representation of ActivityTheme.
     */
    public function __toString(): string
    {
        return $this->getDisplayName();
    }

}
