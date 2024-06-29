<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Product;

class ProductTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $product = Product::create('1', 'sku', 'name');

        $this->assertEquals('1', $product->id());
        $this->assertEquals('sku', $product->sku());
        $this->assertEquals('name', $product->name());
    }
}