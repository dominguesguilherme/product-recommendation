<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use DateTimeImmutable;

interface OrdersByProductFinder
{
    /**
     * @return Order[]
     */
    public function find(string $productId, DateTimeImmutable $startingFrom, DateTimeImmutable $endTo): array;
}