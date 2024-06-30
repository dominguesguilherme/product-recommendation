<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Framework\Id;

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

                $relatedProductId = $relatedProduct->id()->toString();

                $productFrequencies[$relatedProductId]['product'] = $relatedProduct;
                $productFrequencies[$relatedProductId]['count'] = ($productFrequencies[$relatedProductId]['count'] ?? 0) + 1;
            }
        }

        $sortedProducts = $this->sortByFrequency($productFrequencies);

        return array_slice($sortedProducts, 0, $this->topProductsLimit);
    }

    private function isSameProduct(Id $productId, Product $relatedProduct): bool
    {
        return $productId->toString() === $relatedProduct->id()->toString();
    }

    /**
     * @param  array<string, int> $productFrequencies
     * @return Product[]
     */
    private function sortByFrequency(array $productFrequencies): array
    {
        uasort(
            $productFrequencies, function ($a, $b) {
                return $b['count'] <=> $a['count'];
            }
        );
        return array_column($productFrequencies, 'product');
    }
}
