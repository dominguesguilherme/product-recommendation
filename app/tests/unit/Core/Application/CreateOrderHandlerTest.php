<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Application\CreateOrder;
use ProductRecommendation\Core\Application\CreateOrderHandler;
use ProductRecommendation\Core\Application\OrderItemDTO;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrderRepository;

class CreateOrderHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = new InMemoryOrderRepository();
        $handler = new CreateOrderHandler($repository);
        $command = new CreateOrder(
            'order-id',
            new DateTimeImmutable('2021-01-01'),
            [
                new OrderItemDTO('item-id', 'product-id', 10.0, 1),
            ]
        );

        $handler->handle($command);

        $savedOrder = $repository->fetchById('order-id');

        $this->assertSame('order-id', $savedOrder->id());
    }
}

