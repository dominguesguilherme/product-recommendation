<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Order;
use ProductRecommendation\Core\Domain\OrderRepository;
use ProductRecommendation\Core\Infrastructure\Persistence\DoctrineOrderRepository;
use ProductRecommendation\Framework\Id\Id;

class DoctrineOrderRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineOrderRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the EntityManager
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Create an instance of DoctrineOrderRepository with the mocked EntityManager
        $this->repository = new DoctrineOrderRepository($this->entityManager);
    }

    public function testSave(): void
    {
        // Create a mock Order entity
        $order = $this->createMock(Order::class);

        // Expect persist and flush to be called once
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // Call the save method
        $this->repository->save($order);
    }

    public function testFetchById(): void
    {
        // Create a mock Id object
        $orderId = Id::generate();

        // Create a mock Order entity
        $order = $this->createMock(Order::class);

        // Mock the find method of the EntityManager to return the mock Order when called
        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(Order::class, $orderId)
            ->willReturn($order);

        // Call the fetchById method and assert the returned value is the expected mock Order
        $result = $this->repository->fetchById($orderId);
        $this->assertSame($order, $result);
    }
}
