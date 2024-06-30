<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Framework\Id\Id;

class OrderItemTest extends TestCase
{
    public function testCreateOrderItem(): void
    {
        $id = Id::generate();
        $product = Product::create($id, 'sku', 'name');
        $orderItem = OrderItem::create($id, $product, 10.0, 2);

        $this->assertEquals($id, $orderItem->id());
        $this->assertEquals($product, $orderItem->product());
        $this->assertEquals(10.0, $orderItem->unitPrice());
        $this->assertEquals(2, $orderItem->quantity());
    }
}