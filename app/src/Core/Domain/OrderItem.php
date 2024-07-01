<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use Doctrine\ORM\Mapping as ORM;
use ProductRecommendation\Framework\Id\Id;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Id $id;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private ?Order $order;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $productId;

    /**
     * @ORM\Column(type="float")
     */
    private float $unitPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    private function __construct(Id $id, ?Order $order, Id $productId, float $unitPrice, int $quantity)
    {
        $this->id = $id;
        $this->order = $order;
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public static function create(Id $id, Id $productId, float $unitPrice, int $quantity): self
    {
        return new self($id, null, $productId, $unitPrice, $quantity);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function product(): Id
    {
        return $this->productId;
    }

    public function unitPrice(): float
    {
        return $this->unitPrice;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function addToOrder(Order $order): void
    {
        $this->order = $order;
    }
}
