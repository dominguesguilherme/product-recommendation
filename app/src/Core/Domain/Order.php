<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use ProductRecommendation\Framework\Id\Id;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Id $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private float $amount;

    /**
     * @var Collection|OrderItem[]
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "remove"})
     */
    private Collection $items;

    /**
     * @param OrderItem[] $items
     */
    private function __construct(Id $id, DateTimeImmutable $createdAt, float $amount, array $items)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->amount = $amount;
        $this->items = new ArrayCollection($items);
    }

    /**
     * @param OrderItem[] $items
     */
    public static function create(Id $id, DateTimeImmutable $createdAt, array $items = []): self
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

    public function id(): Id
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
        return $this->items->toArray();
    }
}
