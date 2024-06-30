<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Application\RecommendProducts;
use ProductRecommendation\Core\Application\RecommendProductsHandler;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Core\Domain\OrdersByProductFinder;
use ProductRecommendation\Framework\Clock\Infrastructure\FreezedClock;

class RecommendProductsHandlerTest extends TestCase
{
    public function testHandleShouldReturnEmptyArrayWhenNoOrdersFound(): void
    {
        $recommendProducts = new RecommendProducts('product-id');
        $productRecommender = $this->createMock(ProductRecommender::class);
        $ordersByProductFinder = $this->createMock(OrdersByProductFinder::class);
        $frezeedClock = new FreezedClock(new DateTimeImmutable('2021-01-01 00:00:00'));
        $expectedEntTo = $frezeedClock->now();
        $expectedStartFrom = $expectedEntTo->modify('-30 days');
        $recommendProductsHandler = new RecommendProductsHandler(30, $productRecommender, $ordersByProductFinder, $frezeedClock);

        $ordersByProductFinder->expects($this->once())
            ->method('find')
            ->with('product-id', $expectedStartFrom, $expectedEntTo)
            ->willReturn([]);

        $this->assertSame([], $recommendProductsHandler->handle($recommendProducts));
    }

    public function testHandleShouldReturnRecommendedProducts(): void
    {
        $recommendProducts = new RecommendProducts('product-id');
        $productRecommender = $this->createMock(ProductRecommender::class);
        $ordersByProductFinder = $this->createMock(OrdersByProductFinder::class);
        $frezeedClock = new FreezedClock(new DateTimeImmutable('2021-01-01 00:00:00'));
        $expectedEntTo = $frezeedClock->now();
        $expectedStartFrom = $expectedEntTo->modify('-30 days');
        $recommendProductsHandler = new RecommendProductsHandler(30, $productRecommender, $ordersByProductFinder, $frezeedClock);

        $orders = [
            Order::create('order-id', $expectedStartFrom),
            Order::create('another-order-id', $expectedStartFrom),
            Order::create('more-one-order-id', $expectedStartFrom),
        ];

        $ordersByProductFinder->expects($this->once())
            ->method('find')
            ->with('product-id', $expectedStartFrom, $expectedEntTo)
            ->willReturn($orders);

        $productRecommender->expects($this->once())
            ->method('recommendTo')
            ->with('product-id', $orders)
            ->willReturn(['recommended-product-id']);

        $this->assertSame(['recommended-product-id'], $recommendProductsHandler->handle($recommendProducts));
    }
}