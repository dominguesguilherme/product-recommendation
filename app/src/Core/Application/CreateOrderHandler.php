<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\OrderRepository;
use ProductRecommendation\Framework\Id\Id;

class CreateOrderHandler
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function handle(CreateOrder $command): void
    {
        $order = Order::create(Id::fromString($command->id), $command->createdAt);

        foreach ($command->items as $item) {
            $order->addItems(
                OrderItem::create(
                    Id::fromString($item->id),
                    Id::fromString($item->productId),
                    $item->unitPrice, $item->quantity
                )
            );
        }

        $this->repository->save($order);
    }
}