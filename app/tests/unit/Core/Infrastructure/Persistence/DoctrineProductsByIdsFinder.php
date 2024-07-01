<?php

declare(strict_types=1);

namespace Tests\ProductRecommendation\Core\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Core\Domain\Product;
use ProductRecommendation\Core\Infrastructure\Persistence\DoctrineProductsByIdsFinder;
use ProductRecommendation\Framework\Id\Id;

class DoctrineProductsByIdsFinderTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineProductsByIdsFinder $finder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->finder = new DoctrineProductsByIdsFinder($this->entityManager);
    }

    public function testFindReturnsProducts(): void
    {
        $ids = [Id::generate(), Id::generate(), Id::generate()];

        $repository = $this->createMock(ObjectRepository::class);
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Product::class)
            ->willReturn($repository);

        $repository->expects($this->once())
            ->method('findBy')
            ->with(['id' => $ids])
            ->willReturn([
                new Product($ids[0], 'ABC123', 'chuteira'),
                new Product($ids[1], 'ABC123', 'chuteira'),
            ]);

        $products = $this->finder->find($ids);

        $this->assertIsArray($products);
        $this->assertContainsOnlyInstancesOf(Product::class, $products);
    }
}
