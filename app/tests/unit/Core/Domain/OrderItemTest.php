<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Framework\Id\Id;
use ProductRecommendation\Core\Domain\Order;
use DateTimeImmutable;

class OrderItemTest extends TestCase
{
    public function testCreateOrderItem(): void
    {
        $id = Id::generate();
        $order = Order::create(Id::generate(), new DateTimeImmutable('2021-01-01'));
        $product = Product::create($id, 'sku', 'name');
        $orderItem = OrderItem::create($id, $product->id(), 10.0, 2);

        $this->assertEquals($id, $orderItem->id());
        $this->assertEquals($product->id(), $orderItem->product());
        $this->assertEquals(10.0, $orderItem->unitPrice());
        $this->assertEquals(2, $orderItem->quantity());
    }
}