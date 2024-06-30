<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Framework\Id;

class OrderItem
{
    private Id $id;
    private Product $product;
    private float $unitPrice;
    private int $quantity;

    private function __construct(Id $id, Product $product, float $unitPrice, int $quantity)
    {
        $this->id = $id;
        $this->product = $product;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public static function create(Id $id, Product $product, float $unitPrice, int $quantity): self
    {
        return new self($id, $product, $unitPrice, $quantity);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function unitPrice(): float
    {
        return $this->unitPrice;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}