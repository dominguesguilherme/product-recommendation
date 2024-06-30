<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use DateTimeImmutable;
use ProductRecommendation\Framework\Id\Id;

interface OrdersByProductFinder
{
    /**
     * @return Order[]
     */
    public function find(Id $productId, DateTimeImmutable $startingFrom, DateTimeImmutable $endTo): array;
}