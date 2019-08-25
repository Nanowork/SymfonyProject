<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Payment")
     * @ORM\JoinColumn(nullable=true)
     */
    private $payment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Shipment")
     * @ORM\JoinColumn(nullable=true)
     */
    private $shipment;

    /**
     * @ORM\Column(type="datetime")
     * @ORM\JoinColumn(nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @ORM\JoinColumn(nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $itemsTotal;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @ORM\JoinColumn(nullable=true)
     */
    private $itemsPriceTotal;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @ORM\JoinColumn(nullable=true)
     */
    private $priceTotal;

    /**
     * ArrayCollection
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addOrderItem(?OrderItem $orderItem): void
    {
        $this->orderItems->add($orderItem);
    }

    public function removeItem(?OrderItem $orderItem): void
    {
        $this->orderItems->removeElement($orderItem);
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(?Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;

        return $this;
    }

    public function getItemsPriceTotal()
    {
        return $this->itemsPriceTotal;
    }

    public function setItemsPriceTotal($itemsPriceTotal): self
    {
        $this->itemsPriceTotal = $itemsPriceTotal;

        return $this;
    }

    public function getPriceTotal()
    {
        return $this->priceTotal;
    }

    public function setPriceTotal($priceTotal): self
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }
}
