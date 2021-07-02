<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_product;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_client;

    /**
     * @ORM\OneToOne(targetEntity=DeliveryAdress::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_delivery_adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payement_method;

    /**
     * @ORM\Column(type="integer")
     */
    private $Order_id;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduct(): ?Product
    {
        return $this->id_product;
    }

    public function setIdProduct(Product $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdClient(): ?Client
    {
        return $this->id_client;
    }

    public function setIdClient(Client $id_client): self
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getIdDeliveryAdress(): ?DeliveryAdress
    {
        return $this->id_delivery_adress;
    }

    public function setIdDeliveryAdress(DeliveryAdress $id_delivery_adress): self
    {
        $this->id_delivery_adress = $id_delivery_adress;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPayementMethod(): ?string
    {
        return $this->payement_method;
    }

    public function setPayementMethod(string $payement_method): self
    {
        $this->payement_method = $payement_method;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->Order_id;
    }

    public function setOrderId(int $Order_id): self
    {
        $this->Order_id = $Order_id;

        return $this;
    }


}
