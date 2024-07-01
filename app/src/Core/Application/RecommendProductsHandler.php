<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Core\Domain\OrdersByProductFinder;
use ProductRecommendation\Framework\Clock\Domain\Clock;
use ProductRecommendation\Framework\Id\Id;
use ProductRecommendation\Core\Domain\ProductRecommendation;

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
    public function handle(RecommendProducts $recommendProducts): ProductRecommendation
    {
        $endTo = $this->clock->now();
        $startFrom = $endTo->modify(sprintf('-%d days', $this->periodToConsiderInDays));
        $orders = $this->ordersByProductFinder->find(Id::fromString($recommendProducts->productId), $startFrom, $endTo);
        if (count($orders) === 0) {
            return new ProductRecommendation([]);
        }

        return new ProductRecommendation(
            $this->productRecommender->recommendTo(Id::fromString($recommendProducts->productId), $orders)
        );
    }
}