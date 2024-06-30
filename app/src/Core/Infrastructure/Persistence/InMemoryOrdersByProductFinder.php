<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use DateTimeImmutable;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrdersByProductFinder;

class InMemoryOrdersByProductFinder implements OrdersByProductFinder
{
    /**
     * @param Order[] $orders
     */
    public function __construct(private array $orders = [])
    {
    }

    /**
     * @return Order[]
     */
    public function find(string $productId, DateTimeImmutable $startingFrom, DateTimeImmutable $endTo): array
    {
        $ordersFound = [];
        foreach ($this->orders as $order) {
            if ($order->createdAt() >= $startingFrom && $order->createdAt() <= $endTo) {
                foreach ($order->items() as $item) {
                    if ($item->product()->id() === $productId) {
                        $ordersFound[] = $order;
                    }
                }
            }
        }

        return $ordersFound;
    }
}