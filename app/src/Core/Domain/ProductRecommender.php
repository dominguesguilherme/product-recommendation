<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Framework\Id\Id;

interface ProductRecommender
{
    /**
     * @param  Order[] $orders
     * @return Product[]
     */
    public function recommendTo(Id $productId, array $orders): array;
}