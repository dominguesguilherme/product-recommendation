<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

interface OrderRepository
{
    public function save(Order $order): void;

    public function fetchById(string $id): ?Order;
}