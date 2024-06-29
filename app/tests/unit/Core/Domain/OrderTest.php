<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;

class OrderTest extends TestCase
{
    public function testCreateShouldCreateOrder(): void
    {
        $createdAt = new DateTimeImmutable();
        $order = Order::create('1', $createdAt);

        $this->assertEquals('1', $order->id());
        $this->assertEquals($createdAt, $order->createdAt());
        $this->assertEquals(0, $order->amount());
        $this->assertEquals([], $order->items());
    }

    public function testAddItemsShouldAddItensAndCalculateAmount(): void
    {
        $product = Product::create('1', 'sku', 'name');
        $orderItem = OrderItem::create('1', $product, 10.0, 2);
        $createdAt = new DateTimeImmutable();
        $order = Order::create('1', $createdAt);

        $order->addItems($orderItem);

        $this->assertEquals('1', $order->id());
        $this->assertEquals([$orderItem], $order->items());
        $this->assertEquals($createdAt, $order->createdAt());
        $this->assertEquals(20.0, $order->amount());
    }
}