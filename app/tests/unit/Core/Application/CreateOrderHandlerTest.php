<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Application\CreateOrder;
use ProductRecommendation\Core\Application\CreateOrderHandler;
use ProductRecommendation\Core\Application\OrderItemDTO;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrderRepository;
use ProductRecommendation\Framework\Id;

class CreateOrderHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = new InMemoryOrderRepository();
        $handler = new CreateOrderHandler($repository);
        $command = new CreateOrder(
            'c5daa002-7215-4cf6-a3f2-525bc32c6e66',
            new DateTimeImmutable('2021-01-01'),
            [
                new OrderItemDTO(Id::generate()->toString(), Id::generate()->toString(), 10.0, 1),
            ]
        );

        $handler->handle($command);

        $savedOrder = $repository->fetchById(Id::fromString('c5daa002-7215-4cf6-a3f2-525bc32c6e66'));

        $this->assertSame('c5daa002-7215-4cf6-a3f2-525bc32c6e66', $savedOrder->id()->toString());
    }
}

