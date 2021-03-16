<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("list")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups("list", "details")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     */
    private $color;

    /**
     * @ORM\Column(type="float")
     * @Groups("list", "details")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups("details")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("list", "details")
     */
    private $storageCapacity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     */
    private $operatingSystem;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Groups("list", "details")
     */
    private $screenSize;

    /**
     * @ORM\ManyToMany(targetEntity=Customer::class, inversedBy="products")
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getStorageCapacity(): ?string
    {
        return $this->storageCapacity;
    }

    public function setStorageCapacity(string $storageCapacity): self
    {
        $this->storageCapacity = $storageCapacity;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screenSize;
    }

    public function setScreenSize(string $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        $this->customers->removeElement($customer);

        return $this;
    }
}
