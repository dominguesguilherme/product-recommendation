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
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Framework\Clock\Infrastructure\FreezedClock;
use ProductRecommendation\Framework\Id\Id;

class RecommendProductsHandlerTest extends TestCase
{
    public function testHandleShouldReturnEmptyArrayWhenNoOrdersFound(): void
    {
        $recommendProductId = Id::generate()->toString();
        $recommendProducts = new RecommendProducts($recommendProductId);
        $productRecommender = $this->createMock(ProductRecommender::class);
        $ordersByProductFinder = $this->createMock(OrdersByProductFinder::class);
        $frezeedClock = new FreezedClock(new DateTimeImmutable('2021-01-01 00:00:00'));
        $expectedEntTo = $frezeedClock->now();
        $expectedStartFrom = $expectedEntTo->modify('-30 days');
        $recommendProductsHandler = new RecommendProductsHandler(30, $productRecommender, $ordersByProductFinder, $frezeedClock);

        $ordersByProductFinder->expects($this->once())
            ->method('find')
            ->with($recommendProductId, $expectedStartFrom, $expectedEntTo)
            ->willReturn([]);

        $this->assertSame([], $recommendProductsHandler->handle($recommendProducts));
    }

    public function testHandleShouldReturnRecommendedProducts(): void
    {
        $recommendProductId = Id::generate()->toString();
        $recommendProducts = new RecommendProducts($recommendProductId);
        $productRecommender = $this->createMock(ProductRecommender::class);
        $ordersByProductFinder = $this->createMock(OrdersByProductFinder::class);
        $frezeedClock = new FreezedClock(new DateTimeImmutable('2021-01-01 00:00:00'));
        $expectedEntTo = $frezeedClock->now();
        $expectedStartFrom = $expectedEntTo->modify('-30 days');
        $recommendProductsHandler = new RecommendProductsHandler(30, $productRecommender, $ordersByProductFinder, $frezeedClock);
        $expectedRecommended = [Product::create(Id::fromString($recommendProductId), '123', 'product')];

        $orders = [
            Order::create(Id::generate(), $expectedStartFrom),
            Order::create(Id::generate(), $expectedStartFrom),
            Order::create(Id::generate(), $expectedStartFrom),
        ];

        $ordersByProductFinder->expects($this->once())
            ->method('find')
            ->with($recommendProductId, $expectedStartFrom, $expectedEntTo)
            ->willReturn($orders);

        $productRecommender->expects($this->once())
            ->method('recommendTo')
            ->with($recommendProductId, $orders)
            ->willReturn($expectedRecommended);

        $result = $recommendProductsHandler->handle($recommendProducts);

        $this->assertSame($expectedRecommended, $result);
    }
}