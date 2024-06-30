<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Framework\Clock\Infrastructure;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ProductRecommendation\Framework\Clock\Infrastructure\FreezedClock;

class FreezedClockTest extends TestCase
{
    public function testShouldReturnTheCorrectDateNow() : void
    {
        $date = new DateTimeImmutable();
        $systemClock = new FreezedClock($date);

        $this->assertEquals($date, $systemClock->now());
    }
}
