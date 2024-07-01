<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\AbstractQuery;
use ProductRecommendation\Core\Infrastructure\Persistence\DoctrineOrdersByProductFinder;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Framework\Id\Id;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class DoctrineOrdersByProductFinderTest extends TestCase
{
    public function testFindShouldReturnOrdersSuccessfully(): void
    {
        $productId = Id::generate();
        $startFrom = new DateTimeImmutable('2021-01-01');
        $endTo = new DateTimeImmutable('2021-01-20');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $query = $this->createMock(AbstractQuery::class);

        $orders = [
            Order::create(Id::generate(), new DateTimeImmutable('2021-01-10')),
            Order::create(Id::generate(), new DateTimeImmutable('2021-01-15')),
        ];

        $entityManager->method('createQuery')
            ->willReturn($query);

        $query->method('setParameter')
            ->willReturnSelf();
        $query->method('getResult')
            ->willReturn($orders);

        $finder = new DoctrineOrdersByProductFinder($entityManager);

        $result = $finder->find($productId, $startFrom, $endTo);

        $this->assertEquals($orders, $result);
    }
}
