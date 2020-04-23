<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShoppingCartRepository")
 */
class ShoppingCart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $coocieId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="shoppingCarts")
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modelSize;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="shoppingCarts")
     */
    private $cartProduct;



    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $productId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    public function __construct()
    {
        $this->cartProduct = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoocieId(): ?string
    {
        return $this->coocieId;
    }

    public function setCoocieId(string $coocieId): self
    {
        $this->coocieId = $coocieId;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getModelSize(): ?string
    {
        return $this->modelSize;
    }

    public function setModelSize(?string $modelSize): self
    {
        $this->modelSize = $modelSize;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getCartProduct(): Collection
    {
        return $this->cartProduct;
    }

    public function addCartProduct(Product $cartProduct): self
    {
        if (!$this->cartProduct->contains($cartProduct)) {
            $this->cartProduct[] = $cartProduct;
        }

        return $this;
    }

    public function removeCartProduct(Product $cartProduct): self
    {
        if ($this->cartProduct->contains($cartProduct)) {
            $this->cartProduct->removeElement($cartProduct);
        }

        return $this;
    }



    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
