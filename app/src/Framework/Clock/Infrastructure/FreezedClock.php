<?php

declare(strict_types=1);

namespace ProductRecommendation\Framework\Clock\Infrastructure;

use DateTimeImmutable;
use ProductRecommendation\Framework\Clock\Domain\Clock;

class FreezedClock implements Clock
{
    private DateTimeImmutable $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function now() : DateTimeImmutable
    {
        return $this->date;
    }
}
