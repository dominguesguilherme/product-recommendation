<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Domain;

use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

use ProductRecommendation\Core\Domain\HistoricalProductRecommender;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderItem;
use ProductRecommendation\Core\Domain\Product;
use Ramsey\Uuid\Uuid;

class HistoricalProductRecommenderTest extends TestCase
{
    private function createOrder(string $id, array $products): Order
    {
        $order = Order::create($id, new DateTimeImmutable());

        foreach ($products as $product) {
            $order->addItems(OrderItem::create(Uuid::uuid4()->toString(), $product, 10, 1));
        }

        return $order;
    }

    public function testRecommendToShouldReturnTopRecommendedProducts()
    {
        $chuteira = Product::create('1', 'ABC123', 'Chuteira');
        $meia = Product::create('2', 'DEF456', 'Meia');
        $bola = Product::create('3', 'GHI789', 'Bola de futebol');

        $order1 = $this->createOrder('1', [$chuteira, $meia]);
        $order2 = $this->createOrder('2', [$chuteira, $meia]);
        $order3 = $this->createOrder('3', [$chuteira, $meia, $bola]);

        $expectedRecommendations = [
            $meia,
            $bola
        ];

        $recommender = new HistoricalProductRecommender(5);
        $recommendations = $recommender->recommendTo($chuteira->id(), [$order1, $order2, $order3]);

        $this->assertEquals($expectedRecommendations, $recommendations);
    }

    public function testRecommendToShouldReturnTopRecommendedProductsWhenThereAreMoreProductsThanTheLimit()
    {
        $chuteira = Product::create('1', 'ABC123', 'Chuteira');
        $meia = Product::create('2', 'DEF456', 'Meia');
        $bola = Product::create('3', 'GHI789', 'Bola de futebol');
        $tenis = Product::create('4', 'JKL012', 'Tênis');

        $order1 = $this->createOrder('1', [$chuteira, $meia]);
        $order2 = $this->createOrder('2', [$chuteira, $meia]);
        $order3 = $this->createOrder('3', [$chuteira, $meia, $bola]);
        $order4 = $this->createOrder('4', [$chuteira, $meia, $bola, $tenis]);

        $expectedRecommendations = [
            $meia,
            $bola,
        ];

        $recommender = new HistoricalProductRecommender(2);
        $recommendations = $recommender->recommendTo($chuteira->id(), [$order1, $order2, $order3, $order4]);

        $this->assertEquals($expectedRecommendations, $recommendations);
    }

    public function testRecommendToShouldReturnEmptyArrayWhenThereAreNoOrders()
    {
        $chuteira = Product::create('1', 'ABC123', 'Chuteira');

        $recommender = new HistoricalProductRecommender(5);
        $recommendations = $recommender->recommendTo($chuteira->id(), []);

        $this->assertEmpty($recommendations);
    }

    public function testRecommendToShouldReturnEmptyArrayWhenThereAreNoRelatedProducts()
    {
        $chuteira = Product::create('1', 'ABC123', 'Chuteira');

        $order1 = $this->createOrder('1', [$chuteira]);
        $order2 = $this->createOrder('1', [$chuteira]);

        $recommender = new HistoricalProductRecommender(5);
        $recommendations = $recommender->recommendTo($chuteira->id(), [$order1, $order2]);

        $this->assertEmpty($recommendations);
    }
}