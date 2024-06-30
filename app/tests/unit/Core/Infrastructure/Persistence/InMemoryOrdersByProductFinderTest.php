<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrdersByProductFinder;

class InMemoryOrdersByProductFinderTest extends TestCase
{
    const EXPECTED_PRODUCT_ID = '1';
    private function nonExpectedOrders(): array
    {
        return [
            Order::create('order-different-product-id', new DateTimeImmutable('2021-01-01'), [
                OrderItem::create('item-id', Product::create('2', '456DEF', 'Meia'), 10, 1)
            ]),
            Order::create('another-order-different-product-id', new DateTimeImmutable('2021-01-01'), [
                OrderItem::create('item-id', Product::create('3', '789GHI', 'Bola'), 10, 1)
            ]),
            Order::create('more-one-order-different-product-id', new DateTimeImmutable('2021-01-03'), [
                OrderItem::create('item-id', Product::create($this::EXPECTED_PRODUCT_ID, '123ABC', 'chuteira'), 10, 1)
            ]),
            Order::create('the-second-order-different-product-id', new DateTimeImmutable('2020-12-31'), [
                OrderItem::create('item-id', Product::create($this::EXPECTED_PRODUCT_ID, '123ABC', 'chuteira'), 10, 1)
            ]),
        ];
    }

    public function testFindOrdersByProductShouldReturnEmptyArrayWhenNoOrdersFound(): void
    {
        $startFrom = new DateTimeImmutable('2021-01-01');
        $endTo = new DateTimeImmutable('2021-01-02');
        $finder = new InMemoryOrdersByProductFinder($this->nonExpectedOrders());

        $foundOrders = $finder->find($this::EXPECTED_PRODUCT_ID, $startFrom, $endTo);

        $this->assertEmpty($foundOrders);
    }

    public function testFindOrdersByProductShouldReturnOrdersWhenOrdersFound(): void
    {
        $chuteira = Product::create($this::EXPECTED_PRODUCT_ID, '123ABC', 'Chuteira');

        $startFrom = new DateTimeImmutable('2021-01-01');
        $endTo = new DateTimeImmutable('2021-01-02');
        $expectedOrders = [
            Order::create('order-id', new DateTimeImmutable('2021-01-01'), [OrderItem::create('item-id', $chuteira, 10, 1)]),
            Order::create('another-order-id', new DateTimeImmutable('2021-01-01'), [OrderItem::create('item-id', $chuteira, 10, 1)]),
            Order::create('more-one-order-id', new DateTimeImmutable('2021-01-01'), [OrderItem::create('item-id', $chuteira, 10, 1)]),
        ];
        $nonExpectedOrders = $this->nonExpectedOrders();

        $finder = new InMemoryOrdersByProductFinder(array_merge($expectedOrders, $nonExpectedOrders));

        $foundOrders = $finder->find($chuteira->id(), $startFrom, $endTo);

        $this->assertEquals($expectedOrders, $foundOrders);
    }
}

