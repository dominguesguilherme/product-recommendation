<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use ProductRecommendation\Core\Domain\ProductsByIdsFinder;
use ProductRecommendation\Core\Domain\Product;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineProductsByIdsFinder implements ProductsByIdsFinder
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param Id[] $ids
     * @return Product[]
     */
    public function find(array $ids): array
    {
        $products = $this->entityManager->getRepository(Product::class)->findBy(['id' => $ids]);

        return $products;
    }
}