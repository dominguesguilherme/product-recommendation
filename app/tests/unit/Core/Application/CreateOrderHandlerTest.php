<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Application\CreateOrder;
use ProductRecommendation\Core\Application\CreateOrderHandler;
use ProductRecommendation\Core\Application\OrderItemDTO;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrderRepository;
use ProductRecommendation\Framework\Id\Id;

class CreateOrderHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = new InMemoryOrderRepository();
        $handler = new CreateOrderHandler($repository);
        $command = new CreateOrder(
            [
                new OrderItemDTO(Id::generate()->toString(), 10.0, 1),
            ]
        );

        $handler->handle($command);

        $savedOrder = $repository->fetchById(Id::fromString($command->id));

        $this->assertSame($command->id, $savedOrder->id()->toString());
    }
}

