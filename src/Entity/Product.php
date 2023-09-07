<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?string $packaging = null;

    #[ORM\Column]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?int $price = null;

    #[ORM\Column]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?int $supplyTreshold = null;

    #[ORM\Column]
    #[groups(["getProducts", "getEntries","getSuppliers"])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[groups(["getProducts","getSuppliers"])]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[groups(["getProducts","getSuppliers"])]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Supplier::class, mappedBy: 'products')]
    #[groups(["getProducts"])]
    private Collection $suppliers;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Entry::class)]
    private Collection $entries;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->price = 0;
        $this->getSupplyTreshold = 0;
        $this->quantity = 0;
        $this->suppliers = new ArrayCollection();
        $this->entries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPackaging(): ?string
    {
        return $this->packaging;
    }

    public function setPackaging(string $packaging): static
    {
        $this->packaging = $packaging;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSupplyTreshold(): ?int
    {
        return $this->supplyTreshold;
    }

    public function setSupplyTreshold(int $supplyTreshold): static
    {
        $this->supplyTreshold = $supplyTreshold;

        return $this;
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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): static
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers->add($supplier);
            $supplier->addProduct($this);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): static
    {
        if ($this->suppliers->removeElement($supplier)) {
            $supplier->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Entry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): static
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
            $entry->setProduct($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): static
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getProduct() === $this) {
                $entry->setProduct(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
