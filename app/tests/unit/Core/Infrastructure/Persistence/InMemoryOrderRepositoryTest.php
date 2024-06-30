<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrderRepository;
use ProductRecommendation\Framework\Id\Id;

class InMemoryOrderRepositoryTest extends TestCase
{
    public function testSaveOrderShouldSaveOrderSuccessfully(): void
    {
        $order = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $orderRepository = new InMemoryOrderRepository();

        $orderRepository->save($order);

        $this->assertSame($order, $orderRepository->orders[$order->id()->toString()]);
    }

    public function testFetchOrderByIdShouldGetCorrectOrder(): void
    {
        $order = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $anotherOrder = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $moreOneOrder = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));

        $orderRepository = new InMemoryOrderRepository([$order, $anotherOrder, $moreOneOrder]);

        $fetchedOrder = $orderRepository->fetchById($order->id());

        $this->assertSame($order, $fetchedOrder);
    }

    public function testFetchOrderByIdShouldReturnNullWhenOrderNotFound(): void
    {
        $order = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $anotherOrder = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $moreOneOrder = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));

        $orderRepository = new InMemoryOrderRepository([$order, $anotherOrder, $moreOneOrder]);

        $fetchedOrder = $orderRepository->fetchById(Id::generate());

        $this->assertNull($fetchedOrder);
    }
}