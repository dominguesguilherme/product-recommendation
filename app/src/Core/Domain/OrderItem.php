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
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Id $id;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private Order $order;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private Product $product;

    /**
     * @ORM\Column(type="float")
     */
    private float $unitPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    private function __construct(Id $id, Order $order, Product $product, float $unitPrice, int $quantity)
    {
        $this->id = $id;
        $this->order = $order;
        $this->product = $product;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public static function create(Id $id, Order $order, Product $product, float $unitPrice, int $quantity): self
    {
        return new self($id, $order, $product, $unitPrice, $quantity);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function unitPrice(): float
    {
        return $this->unitPrice;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
