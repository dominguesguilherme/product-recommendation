<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\OrderRepository;
use ProductRecommendation\Core\Domain\Product;

class CreateOrderHandler
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function handle(CreateOrder $command): void
    {

        $order = Order::create($command->id, $command->createdAt);

        foreach ($command->items as $item) {
            $order->addItems(OrderItem::create($item->id, Product::create($item->productId, "", ""), $item->unitPrice, $item->quantity));
        }

        $this->repository->save($order);
    }
}