<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;

class OrderItemTest extends TestCase
{
    public function testCreateOrderItem(): void
    {
        $product = Product::create('1', 'sku', 'name');
        $orderItem = OrderItem::create('1', $product, 10.0, 2);

        $this->assertEquals('1', $orderItem->id());
        $this->assertEquals($product, $orderItem->product());
        $this->assertEquals(10.0, $orderItem->unitPrice());
        $this->assertEquals(2, $orderItem->quantity());
    }
}