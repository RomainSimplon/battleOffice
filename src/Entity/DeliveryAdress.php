<?php

namespace App\Entity;

use App\Repository\DeliveryAdressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryAdressRepository::class)
 */
class DeliveryAdress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $comp_adress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompAdress(): ?string
    {
        return $this->comp_adress;
    }

    public function setCompAdress(string $comp_adress): self
    {
        $this->comp_adress = $comp_adress;

        return $this;
    }
}
