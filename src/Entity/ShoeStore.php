<?php

namespace App\Entity;

use App\Repository\ShoeStoreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShoeStoreRepository::class)
 * @ORM\Table(name="shoe_stores")
 */
class ShoeStore
{
    public function __construct(Shoe $shoe, string $store, int $price)
    {
        $this->shoe = $shoe;
        $this->store = $store;
        $this->price = $price;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Shoe::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $shoe;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $store;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoe(): Shoe
    {
        return $this->shoe;
    }

    public function setShoe(?Shoe $shoe): self
    {
        $this->shoe = $shoe;

        return $this;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function setStore(string $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
