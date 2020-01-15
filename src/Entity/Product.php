<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=2000)
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
     * @ORM\Column(type="float")
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
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

    public function setDescription(string $description): self
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

    public function setDiscountPrice(float $discountPrice): self
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
}
