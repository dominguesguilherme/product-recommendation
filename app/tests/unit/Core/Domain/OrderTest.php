<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Framework\Id\Id;

class OrderTest extends TestCase
{
    public function testCreateShouldCreateOrder(): void
    {
        $createdAt = new DateTimeImmutable();
        $id = Id::generate();
        $order = Order::create($id, $createdAt);

        $this->assertEquals($id, $order->id());
        $this->assertEquals($createdAt, $order->createdAt());
        $this->assertEquals(0, $order->amount());
        $this->assertEquals([], $order->items());
    }

    public function testAddItemsShouldAddItensAndCalculateAmount(): void
    {
        $id = Id::generate();
        $product = Product::create(Id::generate(), 'sku', 'name');
        $orderItem = OrderItem::create(Id::generate(), $product, 10.0, 2);
        $createdAt = new DateTimeImmutable();
        $order = Order::create($id, $createdAt);

        $order->addItems($orderItem);

        $this->assertEquals($id, $order->id());
        $this->assertEquals([$orderItem], $order->items());
        $this->assertEquals($createdAt, $order->createdAt());
        $this->assertEquals(20.0, $order->amount());
    }
}