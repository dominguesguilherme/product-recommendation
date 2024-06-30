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
use ProductRecommendation\Framework\Id\Id;

class HistoricalProductRecommenderTest extends TestCase
{
    private function createOrder(array $products): Order
    {
        $order = Order::create(Id::generate(), new DateTimeImmutable());

        foreach ($products as $product) {
            $order->addItems(OrderItem::create(Id::generate(), $product, 10, 1));
        }

        return $order;
    }

    public function testRecommendToShouldReturnTopRecommendedProducts()
    {
        $chuteira = Product::create(Id::generate(), 'ABC123', 'Chuteira');
        $meia = Product::create(Id::generate(), 'DEF456', 'Meia');
        $bola = Product::create(Id::generate(), 'GHI789', 'Bola de futebol');

        $order1 = $this->createOrder([$chuteira, $meia]);
        $order2 = $this->createOrder([$chuteira, $meia]);
        $order3 = $this->createOrder([$chuteira, $meia, $bola]);

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
        $chuteira = Product::create(Id::generate(), 'ABC123', 'Chuteira');
        $meia = Product::create(Id::generate(), 'DEF456', 'Meia');
        $bola = Product::create(Id::generate(), 'GHI789', 'Bola de futebol');
        $tenis = Product::create(Id::generate(), 'JKL012', 'Tênis');

        $order1 = $this->createOrder([$chuteira, $meia]);
        $order2 = $this->createOrder([$chuteira, $meia]);
        $order3 = $this->createOrder([$chuteira, $meia, $bola]);
        $order4 = $this->createOrder([$chuteira, $meia, $bola, $tenis]);

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
        $chuteira = Product::create(Id::generate(), 'ABC123', 'Chuteira');

        $recommender = new HistoricalProductRecommender(5);
        $recommendations = $recommender->recommendTo($chuteira->id(), []);

        $this->assertEmpty($recommendations);
    }

    public function testRecommendToShouldReturnEmptyArrayWhenThereAreNoRelatedProducts()
    {
        $chuteira = Product::create(Id::generate(), 'ABC123', 'Chuteira');

        $order1 = $this->createOrder([$chuteira]);
        $order2 = $this->createOrder([$chuteira]);

        $recommender = new HistoricalProductRecommender(5);
        $recommendations = $recommender->recommendTo($chuteira->id(), [$order1, $order2]);

        $this->assertEmpty($recommendations);
    }
}