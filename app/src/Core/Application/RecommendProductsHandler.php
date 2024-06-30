<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use DateTimeImmutable;
use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Core\Domain\OrdersByProductFinder;
use ProductRecommendation\Framework\Clock\Domain\Clock;

class RecommendProductsHandler
{
    public function __construct(
        private int $periodToConsiderInDays,
        private ProductRecommender $productRecommender,
        private OrdersByProductFinder $ordersByProductFinder,
        private Clock $clock
    )
    {
    }

    /** * @return Product[] */
    public function handle(RecommendProducts $recommendProducts): array
    {
        $endTo = $this->clock->now();
        $startFrom = $endTo->modify(sprintf('-%d days', $this->periodToConsiderInDays));
        $orders = $this->ordersByProductFinder->find($recommendProducts->productId, $startFrom, $endTo);
        if (count($orders) === 0) {
            return [];
        }

        return $this->productRecommender->recommendTo($recommendProducts->productId, $orders);
    }
}