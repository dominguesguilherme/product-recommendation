<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Framework\Id\Id;

class HistoricalProductRecommender implements ProductRecommender
{
    private int $topProductsLimit;

    public function __construct($topProductsLimit)
    {
        $this->topProductsLimit = $topProductsLimit;
    }

    /**
     * @param  Order[] $orders
     * @return Product[]
     */
    public function recommendTo(Id $productId, array $orders): array
    {
        $productFrequencies = [];

        foreach ($orders as $order) {
            foreach ($order->items() as $orderItem) {
                $relatedProduct = $orderItem->product();

                if ($this->isSameProduct($productId, $relatedProduct)) {
                    continue;
                }

                if (!isset($productFrequencies[$relatedProduct->toString()])) {
                    $productFrequencies[$relatedProduct->toString()] = 0;
                    continue;
                }

                $productFrequencies[$relatedProduct->toString()]++;
            }
        }

        $sortedProducts = $this->sortByFrequency($productFrequencies);

        return array_slice($sortedProducts, 0, $this->topProductsLimit);
    }

    private function isSameProduct(Id $productId, Id $relatedProduct): bool
    {
        return $productId->toString() === $relatedProduct->toString();
    }

    /**
     * @param  array<string, int> $productFrequencies
     * @return array<string, int>
     */
    private function sortByFrequency(array $productFrequencies): array
    {
        arsort($productFrequencies);

        return array_keys($productFrequencies);
    }
}
