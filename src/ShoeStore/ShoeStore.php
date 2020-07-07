<?php

namespace App\ShoeStore;

use App\Shoe\Shoe;

class ShoeStore
{
    public int    $id;
    public Shoe   $shoe;
    public string $store;
    public int    $price;

    public function __construct(Shoe $shoe, string $store, int $price)
    {
        $this->id    = 0;
        $this->shoe  = $shoe;
        $this->store = $store;
        $this->price = $price;
    }
    public function getPriceCurrency() : ?float
    {
        $price = $this->price;
        if ($price < 1) return null;
        return (float)$price / 100.00;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getShoe(): Shoe
    {
        return $this->shoe;
    }

    public function setShoe(Shoe $shoe): self
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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
