<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use DateTimeImmutable;
use ProductRecommendation\Core\Application\OrderItemDTO;

final class CreateOrder
{
    /**
     * @param OrderItemDTO[] $items
     */
    public function __construct(public string $id, public DateTimeImmutable $createdAt, public array $items)
    {

    }
}