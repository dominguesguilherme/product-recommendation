<?php

declare(strict_types=1);

namespace ProductRecommendation\Framework\Clock\Infrastructure;

use DateInterval;
use DateTimeImmutable;
use ProductRecommendation\Framework\Clock\Domain\Clock;

class SystemClock implements Clock
{
    public function now() : DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function threeMonthsAgo() : DateTimeImmutable
    {
        $currentDate = new DateTimeImmutable();

        return $currentDate->sub(new DateInterval('P3M'));
    }
}
