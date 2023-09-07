<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[groups(["getOrders","getItems"])]
    private ?string $orderNumber = null;

    #[ORM\Column(length: 255)]
    #[groups(["getOrders","getItems"])]
    private ?string $customerName = null;

    #[ORM\Column(nullable: true)]
    #[groups(["getOrders","getItems"])]
    private ?int $customerContact = null;

    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?float $subAmount = null;

    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?float $discount = null;

    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?float $orderCost = null;

    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?float $paid = null;

    #[ORM\Column]
    #[groups(["getOrders","getItems"])]
    private ?float $dueAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[groups(["getOrders","getItems"])]
    private ?string $orderStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[groups(["getOrders","getItems"])]
    private ?string $paymentType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[groups(["getOrders","getItems"])]
    private ?\DateTimeInterface $created = null;

    #[ORM\OneToMany(mappedBy: 'Invoice', targetEntity: Item::class, orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInvoice($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getInvoice() === $this) {
                $item->setInvoice(null);
            }
        }

        return $this;
    }
}
