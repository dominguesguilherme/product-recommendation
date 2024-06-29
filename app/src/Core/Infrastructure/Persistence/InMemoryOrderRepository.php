<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderRepository;

class InMemoryOrderRepository implements OrderRepository
{
    /**
     * @var Order[]
     */
    public array $orders = [];

    /**
     * @param Order[] $orders
     */
    public function __construct(array $orders = [])
    {
        foreach ($orders as $order) {
            $this->orders[$order->id()] = $order;
        }
    }

    public function save(Order $order): void
    {
        $this->orders[$order->id()] = $order;
    }


    public function fetchById(string $id): ?Order
    {
        if (!isset($this->orders[$id])) {
            return null;
        }

        return $this->orders[$id];
    }
}