<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use OpenApi\Annotations as OA;
use Hateoas\Configuration\Annotation as Hateoas;
use Hateoas\Configuration\Annotation\Exclusion;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(
 *      fields={"email"},
 *      message="Email address already used for another account."
 * )
 * @OA\Schema()
 *
 * @Hateoas\Relation(
 *     "List of all your customers",
 *     href= @Hateoas\Route(
 *     "api_customers_list",
 *     parameters={"id"="expr(object.getId())"}
 *     ),
 *     exclusion=@Exclusion(groups="item")
 * )
 * @Hateoas\Relation(
 *     "Read the details",
 *     href= @Hateoas\Route(
 *     "api_customers_item",
 *     parameters={"id"="expr(object.getId())"}
 *     ),
 *     exclusion=@Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *     "Create a new customer",
 *     href= @Hateoas\Route(
 *     "api_customers_create"
 *     ),
 *     exclusion=@Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *     "Delete this customer",
 *     href= @Hateoas\Route(
 *     "api_customers_delete",
 *     parameters={"id"="expr(object.getId())"}
 *     ),
 *     exclusion=@Exclusion(groups="item")
 * )
 * @Hateoas\Relation(
 *     "Edit this customer",
 *     href= @Hateoas\Route(
 *     "api_customers_edit",
 *     parameters={"id"="expr(object.getId())"}
 *     ),
 *     exclusion=@Exclusion(groups="item")
 * )
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
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "item"})
     */
    private $id;

    /**
     * @OA\Property (type="string", format="email")
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"item"})
     */
    private $email;

    /**
     * @OA\Property (type="string")
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=100)
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "item"})
     */
    private $lastName;

    /**
     * @OA\Property (type="string")
     * @var string
     * @ORM\Column(type="string")
     * @Assert\Length(min=2, max=100)
     * @Assert\NotBlank
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "item"})
     */
    private $firstName;

    /**
     * @OA\Property (type="string", format="date-time")
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"item"})
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
    private $user;


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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
