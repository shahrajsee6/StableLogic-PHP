<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country", indexes={@ORM\Index(name="continent_id", columns={"continent_id"}), @ORM\Index(name="FKcountry50845", columns={"continent_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alpha_2_code", type="string", length=2, nullable=false)
     */
    private $alpha2Code;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=5, nullable=false)
     */
    private $currencyCode;

    /**
     * @var Continent
     *
     * @ORM\ManyToOne(targetEntity="Continent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="continent_id", referencedColumnName="id")
     * })
     */
    private $continent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlpha2Code(): ?string
    {
        return $this->alpha2Code;
    }

    public function setAlpha2Code(string $alpha2Code): self
    {
        $this->alpha2Code = $alpha2Code;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): self
    {
        $this->continent = $continent;

        return $this;
    }

    public function getInsertQuery(): string
    {
        return "INSERT INTO country (`name`, `continent_id`, `alpha_2_code`, `currency_code`) VALUES ('" . $this->name . "', " . $this->continent->getId() . ", '" . $this->alpha2Code . "', '" . $this->currencyCode . "')";
    }
}
