<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Core\Domain\Product;

final class ProductRecommendation
{
    /**
     * @param Product[] $products
     */
    public function __construct(public array $products)
    {
    }
}