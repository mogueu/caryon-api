<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $orderNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $customerName = null;

    #[ORM\Column(nullable: true)]
    private ?int $customerContact = null;

    #[ORM\Column]
    private ?float $subAmount = null;

    #[ORM\Column]
    private ?float $discount = null;

    #[ORM\Column]
    private ?float $orderCost = null;

    #[ORM\Column]
    private ?float $paid = null;

    #[ORM\Column]
    private ?float $dueAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orderStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerContact(): ?int
    {
        return $this->customerContact;
    }

    public function setCustomerContact(?int $customerContact): static
    {
        $this->customerContact = $customerContact;

        return $this;
    }

    public function getSubAmount(): ?float
    {
        return $this->subAmount;
    }

    public function setSubAmount(float $subAmount): static
    {
        $this->subAmount = $subAmount;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getOrderCost(): ?float
    {
        return $this->orderCost;
    }

    public function setOrderCost(float $orderCost): static
    {
        $this->orderCost = $orderCost;

        return $this;
    }

    public function getPaid(): ?float
    {
        return $this->paid;
    }

    public function setPaid(float $paid): static
    {
        $this->paid = $paid;

        return $this;
    }

    public function getDueAmount(): ?float
    {
        return $this->dueAmount;
    }

    public function setDueAmount(float $dueAmount): static
    {
        $this->dueAmount = $dueAmount;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?string $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(?string $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }
}
