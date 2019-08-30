<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class CartOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $totalPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shipment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shipment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Payment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param $shipment
     */
    public function setShipment($shipment): void
    {
        $this->shipment = $shipment;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param $payment
     */
    public function setPayment($payment): void
    {
        $this->payment = $payment;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
