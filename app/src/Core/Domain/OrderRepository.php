<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

use ProductRecommendation\Framework\Id;

interface OrderRepository
{
    public function save(Order $order): void;

    public function fetchById(Id $id): ?Order;
}