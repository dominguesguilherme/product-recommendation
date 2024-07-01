<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use Symfony\Component\Validator\Constraints as Assert;
use ProductRecommendation\Framework\Id\Id;

final class OrderItemDTO
{
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

    public function __construct(string $productId, float $unitPrice, int $quantity)
    {
        $this->id = Id::generate()->toString();
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }
}
