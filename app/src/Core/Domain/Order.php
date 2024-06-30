<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Date;

class Order
{
    private string $id;
    private DateTimeImmutable $createdAt;
    private float $amount;
    /** @var OrderItem[] */
    private array $items;

    /**
     * @param OrderItem[] $items
     */
    private function __construct(string $id, DateTimeImmutable $createdAt, float $amount, array $items)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->amount = $amount;
        $this->items = $items;
    }

    /**
     * @param OrderItem[] $items
     */
    public static function create(string $id, DateTimeImmutable $createdAt, array $items = []): self
    {
        $amount = self::calculateAmount($items);
        return new self($id, $createdAt, $amount, $items);
    }

    private static function calculateAmount(array $items): float
    {
        $amount = 0;
        foreach ($items as $item) {
            $amount += $item->unitPrice() * $item->quantity();
        }

        return $amount;
    }

    public function addItems(OrderItem $item): void
    {
        $this->items[] = $item;
        $this->amount += $item->unitPrice() * $item->quantity();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return OrderItem[]
     */
    public function items(): array
    {
        return $this->items;
    }
}