<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

final class OrderItemDTO
{
    public string $id;
    public string $productId;
    public float $unitPrice;
    public int $quantity;

    public function __construct(string $id, string $productId, float $unitPrice, int $quantity)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }
}