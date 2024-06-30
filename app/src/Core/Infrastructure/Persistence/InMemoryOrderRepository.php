<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderRepository;
use ProductRecommendation\Framework\Id\Id;

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
            $this->orders[$order->id()->toString()] = $order;
        }
    }

    public function save(Order $order): void
    {
        $this->orders[$order->id()->toString()] = $order;
    }


    public function fetchById(Id $id): ?Order
    {
        if (!isset($this->orders[$id->toString()])) {
            return null;
        }

        return $this->orders[$id->toString()];
    }
}