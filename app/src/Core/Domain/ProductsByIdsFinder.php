<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

interface ProductsByIdsFinder
{
    /**
     * @param  array<string> $productIds
     * @return Product[]
     */
    public function find(array $productIds): array;
}