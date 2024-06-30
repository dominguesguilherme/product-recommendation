<?php

declare(strict_types=1);

namespace ProductRecommendation\Framework\Clock\Domain;

use DateTimeImmutable;

interface Clock
{
    public function now() : DateTimeImmutable;
}
