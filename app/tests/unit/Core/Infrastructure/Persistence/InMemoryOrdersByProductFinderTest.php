<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Infrastructure\Persistence\InMemoryOrdersByProductFinder;
use ProductRecommendation\Framework\Id;

class InMemoryOrdersByProductFinderTest extends TestCase
{
    const EXPECTED_PRODUCT_ID = 'e681de19-2756-488b-9281-3061ba56e74c';
    private function nonExpectedOrders(): array
    {
        return [
            Order::create(Id::fromString('ca0b141f-633c-4a4b-b639-57b614fce1b1'), new DateTimeImmutable('2021-01-01'), [
                OrderItem::create(Id::generate(), Product::create(Id::fromString('103437f3-6cc2-4a04-99cf-f35c31e00cd0'), '456DEF', 'Meia'), 10, 1)
            ]),
            Order::create(Id::fromString('b09d18c5-6349-4c18-bfd8-4ffa4349378d'), new DateTimeImmutable('2021-01-01'), [
                OrderItem::create(Id::generate(), Product::create(Id::fromString('0fdc2168-b9b4-4b21-aefe-4cd41aa78894'), '789GHI', 'Bola'), 10, 1)
            ]),
            Order::create(Id::fromString('35da7027-9cda-41d6-9434-920e3bdfcdb2'), new DateTimeImmutable('2021-01-03'), [
                OrderItem::create(Id::generate(), Product::create(Id::fromString($this::EXPECTED_PRODUCT_ID), '123ABC', 'chuteira'), 10, 1)
            ]),
            Order::create(Id::fromString('d2296577-02b7-49c5-b801-8c98eaa76bb0'), new DateTimeImmutable('2020-12-31'), [
                OrderItem::create(Id::generate(), Product::create(Id::fromString($this::EXPECTED_PRODUCT_ID), '123ABC', 'chuteira'), 10, 1)
            ]),
        ];
    }

    public function testFindOrdersByProductShouldReturnEmptyArrayWhenNoOrdersFound(): void
    {
        $startFrom = new DateTimeImmutable('2021-01-01');
        $endTo = new DateTimeImmutable('2021-01-02');
        $finder = new InMemoryOrdersByProductFinder($this->nonExpectedOrders());

        $foundOrders = $finder->find(Id::fromString($this::EXPECTED_PRODUCT_ID), $startFrom, $endTo);

        $this->assertEmpty($foundOrders);
    }

    public function testFindOrdersByProductShouldReturnOrdersWhenOrdersFound(): void
    {
        $chuteira = Product::create(Id::fromString($this::EXPECTED_PRODUCT_ID), '123ABC', 'Chuteira');

        $startFrom = new DateTimeImmutable('2021-01-01');
        $endTo = new DateTimeImmutable('2021-01-02');
        $expectedOrders = [
            Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'), [OrderItem::create(Id::generate(), $chuteira, 10, 1)]),
            Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'), [OrderItem::create(Id::generate(), $chuteira, 10, 1)]),
            Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'), [OrderItem::create(Id::generate(), $chuteira, 10, 1)]),
        ];
        $nonExpectedOrders = $this->nonExpectedOrders();

        $finder = new InMemoryOrdersByProductFinder(array_merge($expectedOrders, $nonExpectedOrders));

        $foundOrders = $finder->find($chuteira->id(), $startFrom, $endTo);

        $this->assertEquals($expectedOrders, $foundOrders);
    }
}

