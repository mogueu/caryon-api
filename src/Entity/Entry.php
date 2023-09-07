<?php

namespace App\Entity;

use App\Repository\EntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups(["getEntries"])]
    private ?int $id = null;

    #[ORM\Column]
    #[groups(["getEntries"])]
    private ?int $quantity = null;

    #[ORM\Column]
    #[groups(["getEntries"])]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[groups(["getEntries"])]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[ORM\JoinColumn(nullable: false)]
    #[groups(["getEntries"])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[groups(["getEntries"])]
    private ?Supplier $supplier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[groups(["getEntries"])]
    private ?string $note = null;

    public function __construct()
    {
        $this->created = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }
}
