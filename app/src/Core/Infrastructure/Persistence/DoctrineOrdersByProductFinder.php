<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use ProductRecommendation\Core\Domain\OrdersByProductFinder;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Framework\Id\Id;

class DoctrineOrdersByProductFinder implements OrdersByProductFinder
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return Order[]
     */
    public function find(Id $productId, DateTimeImmutable $startFrom, DateTimeImmutable $endTo): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT o FROM ProductRecommendation\Core\Domain\Order o 
             JOIN o.items oi 
             WHERE oi.productId = :productId 
             AND o.createdAt >= :startFrom 
             AND o.createdAt <= :endTo'
        );
        $query->setParameter('productId', $productId->toString());
        $query->setParameter('startFrom', $startFrom);
        $query->setParameter('endTo', $endTo);

        return $query->getResult();
    }
}
