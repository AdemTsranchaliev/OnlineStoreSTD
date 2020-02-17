<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modelNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $boughtCounter;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Sizes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDetelet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPromotion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discountPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $photoCount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShoe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryId;

    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="product")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ShoppingCart", mappedBy="cartProduct")
     */
    private $shoppingCarts;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModelNumber(): ?string
    {
        return $this->modelNumber;
    }

    public function setModelNumber(string $modelNumber): self
    {
        $this->modelNumber = $modelNumber;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBoughtCounter(): ?int
    {
        return $this->boughtCounter;
    }

    public function setBoughtCounter(int $boughtCounter): self
    {
        $this->boughtCounter = $boughtCounter;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSizes(): ?string
    {
        return $this->Sizes;
    }

    public function setSizes(?string $Sizes): self
    {
        $this->Sizes = $Sizes;

        return $this;
    }

    public function getIsDetelet(): ?bool
    {
        return $this->isDetelet;
    }

    public function setIsDetelet(bool $isDetelet): self
    {
        $this->isDetelet = $isDetelet;

        return $this;
    }

    public function getIsPromotion(): ?bool
    {
        return $this->isPromotion;
    }

    public function setIsPromotion(bool $isPromotion): self
    {
        $this->isPromotion = $isPromotion;

        return $this;
    }

    public function getDiscountPrice(): ?float
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(?float $discountPrice): self
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    public function getPhotoCount(): ?int
    {
        return $this->photoCount;
    }

    public function setPhotoCount(int $photoCount): self
    {
        $this->photoCount = $photoCount;

        return $this;
    }

    public function getIsShoe(): ?bool
    {
        return $this->isShoe;
    }

    public function setIsShoe(bool $isShoe): self
    {
        $this->isShoe = $isShoe;

        return $this;
    }

    public function getCategoryId(): ?Category
    {
        return $this->categoryId;
    }

    public function setCategoryId(?Category $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(OrderProduct $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setProduct($this);
        }

        return $this;
    }

    public function removeOrder(OrderProduct $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getProduct() === $this) {
                $order->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ShoppingCart[]
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->addCartProduct($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts->removeElement($shoppingCart);
            $shoppingCart->removeCartProduct($this);
        }

        return $this;
    }
}
