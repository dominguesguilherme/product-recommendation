<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderItemDTO
{
    /** @Assert\NotBlank(allowNull = false) */
    public string $id;

    /** @Assert\NotBlank(allowNull = false) */
    public string $productId;

    /**
     * @Assert\NotBlank(allowNull = false)
     * @Assert\Type(type="float")
     * @Assert\Positive
     */
    public float $unitPrice;

    /**
     * @Assert\NotBlank(allowNull = false)
     * @Assert\Type(type="integer")
     * @Assert\Positive
     */
    public int $quantity;

    public function __construct(string $id, string $productId, float $unitPrice, int $quantity)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }
}
