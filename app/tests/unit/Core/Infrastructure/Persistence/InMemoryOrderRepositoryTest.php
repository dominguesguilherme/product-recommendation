<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrderRepository;

class InMemoryOrderRepositoryTest extends TestCase
{
    public function testSaveOrderShouldSaveOrderSuccessfully(): void
    {
        $order = Order::create('order-id', new DateTimeImmutable('2021-01-01'));
        $orderRepository = new InMemoryOrderRepository();

        $orderRepository->save($order);

        $this->assertSame($order, $orderRepository->orders[$order->id()]);
    }

    public function testFetchOrderByIdShouldGetCorrectOrder(): void
    {
        $order = Order::create('order-id', new DateTimeImmutable('2021-01-01'));
        $anotherOrder = Order::create('another-order-id', new DateTimeImmutable('2021-01-01'));
        $moreOneOrder = Order::create('more-one-order-id', new DateTimeImmutable('2021-01-01'));

        $orderRepository = new InMemoryOrderRepository([$order, $anotherOrder, $moreOneOrder]);

        $fetchedOrder = $orderRepository->fetchById($order->id());

        $this->assertSame($order, $fetchedOrder);
    }

    public function testFetchOrderByIdShouldReturnNullWhenOrderNotFound(): void
    {
        $order = Order::create('order-id', new DateTimeImmutable('2021-01-01'));
        $anotherOrder = Order::create('another-order-id', new DateTimeImmutable('2021-01-01'));
        $moreOneOrder = Order::create('more-one-order-id', new DateTimeImmutable('2021-01-01'));

        $orderRepository = new InMemoryOrderRepository([$order, $anotherOrder, $moreOneOrder]);

        $fetchedOrder = $orderRepository->fetchById('not-found-order-id');

        $this->assertNull($fetchedOrder);
    }
}