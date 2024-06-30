<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Framework\Id\Id;

class ProductTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $id =  Id::generate();
        $product = Product::create($id, 'sku', 'name');

        $this->assertEquals($id, $product->id());
        $this->assertEquals('sku', $product->sku());
        $this->assertEquals('name', $product->name());
    }
}