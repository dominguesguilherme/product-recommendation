<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Domain\ProductRecommender;
use ProductRecommendation\Framework\Id\Id;
use ProductRecommendation\Core\Domain\ProductsByIdsFinder;

class HistoricalProductRecommender implements ProductRecommender
{
    public function __construct(
        private int $topProductsLimit,
        private ProductsByIdsFinder $productsByIdFinder
    )
    {
    }

    /**
     * @param  Id $productId
     * @param  Order[] $orders
     * @return Product[]
     */
    public function recommendTo(Id $productId, array $orders): array
    {
        $productFrequencies = [];

        foreach ($orders as $order) {
            foreach ($order->items() as $orderItem) {
                $relatedProduct = $orderItem->product();

                if ($productId->toString() !== $relatedProduct->toString()) {
                    $productIdString = $relatedProduct->toString();
                    $productFrequencies[$productIdString] = ($productFrequencies[$productIdString] ?? 0) + 1;
                }
            }
        }

        arsort($productFrequencies);

        $productIds = array_slice(array_keys($productFrequencies), 0, $this->topProductsLimit);

        $products = $this->productsByIdFinder->find(array_map(fn($id) => Id::fromString($id), $productIds));

        usort($products, function ($a, $b) use ($productFrequencies) {
            $idA = $a->id()->toString();
            $idB = $b->id()->toString();
            return ($productFrequencies[$idB] ?? 0) <=> ($productFrequencies[$idA] ?? 0);
        });

        return $products;
    }
}
