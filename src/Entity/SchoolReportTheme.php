<?php

namespace App\Entity;

use App\Repository\SchoolReportThemeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SchoolReportThemeRepository::class)
 */
class SchoolReportTheme
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
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;


    /**
     * Return the School Report Them ID.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Return the School Report Theme name.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * Set the school report Theme name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Return the school report theme version.
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }


    /**
     * Set the school report theme version.
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }


    /**
     * Return the school report theme author.
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }


    /**
     * Set the school report theme author.
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }


    /**
     * Return the school report theme release date.
     * @return \DateTimeInterface|null
     */
    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }


    /**
     * Set the school report theme release date.
     * @param \DateTimeInterface $releaseDate
     * @return $this
     */
    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }


    /**
     * Retuyrn the school report theme identifier.
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }


    /**
     * Set the school report identifier.
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
