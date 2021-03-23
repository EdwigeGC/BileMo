<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;



/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @OA\Schema()
 */
class Customer
{
    /**
     * @OA\Property(
     *     format="int64",
     *     title="ID")
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("list")
     */
    private $id;

    /**
     * @OA\Property (type="string", format="email")
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("item")
     */
    private $email;

    /**
     * @OA\Property (type="string")
     * @var string
     * @ORM\Column(type="string")
     * @Groups("list", "item")
     */
    private $lastName;

    /**
     * @OA\Property (type="string")
     * @var string
     * @ORM\Column(type="string")
     * @Groups("list", "item")
     */
    private $firstName;

    /**
     * @OA\Property (type="string", format="date-time")
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Groups("item")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="customers")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=User::class,  inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastName(): string
    {
        return (string) $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName= $lastName;

        return $this;
    }

    public function getFirstName(): string
    {
        return (string) $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName= $firstName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeUser($this);
        }

        return $this;
    }
}
