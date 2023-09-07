<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups(["getSuppliers","getEntries","getProducts"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[groups(["getSuppliers","getEntries","getProducts"])]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[groups(["getSuppliers","getEntries","getProducts"])]
    private ?string $representative = null;

    #[ORM\Column(nullable: true)]
    #[groups(["getSuppliers","getEntries","getProducts"])]
    private ?int $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[groups(["getSuppliers","getEntries","getProducts"])]
    private ?string $location = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'suppliers')]
    #[groups(["getSuppliers"])]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Entry::class)]
    private Collection $entries;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->entries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getRepresentative(): ?string
    {
        return $this->representative;
    }

    public function setRepresentative(string $representative): static
    {
        $this->representative = $representative;

        return $this;
    }

    public function getContact(): ?int
    {
        return $this->contact;
    }

    public function setContact(?int $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

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
            $entry->setSupplier($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): static
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getSupplier() === $this) {
                $entry->setSupplier(null);
            }
        }

        return $this;
    }
}
