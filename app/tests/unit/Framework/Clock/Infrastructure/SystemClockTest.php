<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Framework\Clock\Infrastructure;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Framework\Clock\Infrastructure\SystemClock;

class SystemClockTest extends TestCase
{
    public function testShouldReturnTheCorrectDateNow() : void
    {
        $systemClock = new SystemClock();
        $currentDate = new DateTimeImmutable();

        $this->assertEquals($currentDate->format('Y-m-d'), $systemClock->now()->format('Y-m-d'));
    }
}
