<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

interface ProductRecommender
{
    /**
     * @param  Order[] $orders
     * @return Product[]
     */
    public function recommendTo(Product $product, array $orders): array;
}