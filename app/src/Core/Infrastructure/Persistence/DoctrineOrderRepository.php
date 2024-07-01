<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderRepository;
use ProductRecommendation\Framework\Id\Id;

class DoctrineOrderRepository implements OrderRepository
{
    private const ENTITY = Order::class;

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function fetchById(Id $id): ?Order
    {
        $order = $this->entityManager->find(self::ENTITY, $id);

        if (! $order instanceof Order) {
            return null;
        }

        return $order;
    }
}