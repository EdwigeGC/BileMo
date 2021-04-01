<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;
use Hateoas\Configuration\Annotation as Hateoas;
use Hateoas\Configuration\Annotation\Exclusion;



/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @OA\Schema()
 *
 * @Hateoas\Relation(
 *     "List of all the products",
 *     href= @Hateoas\Route(
 *     "api_products_list"
 *     ),
 *     exclusion=@Exclusion(groups="item")
 * )
 * @Hateoas\Relation(
 *     "Read the details",
 *     href= @Hateoas\Route(
 *     "api_products_item",
 *     parameters={"id"="expr(object.getId())"}
 *     ),
 *     exclusion=@Exclusion(groups="list")
 * )
 */
class Product
{
    /**
     * @OA\Property(
     *     format="int64",
     *     title="ID")
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list", "item"})
     */
    private $id;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Groups({"list", "item"})
     */
    private $name;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"item"})
     */
    private $color;

    /**
     * @OA\Property (type="number", format="float")
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list", "item"})
     */
    private $price;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Groups({"item"})
     */
    private $reference;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"item"})
     */
    private $brand;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "item"})
     */
    private $storageCapacity;

    /**
     * @OA\Property (type="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"item"})
     */
    private $operatingSystem;

    /**
     * @OA\Property (type="number", format="float")
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Serializer\Groups({"item"})
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
