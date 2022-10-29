<?php

declare(strict_types=1);

namespace Pheature\Test\Model\DateTime;

use Beste\Clock\SystemClock;
use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Model\DateTime\IntervalSegmentFactory;
use Pheature\Model\DateTime\IntervalStrictMatchingSegment;
use PHPUnit\Framework\TestCase;

class IntervalSegmentFactoryTest extends TestCase
{
    private const SEGMENT_ID = 'some_segment';
    private const DATETIME_STRICT_MATCHING_SEGMENT = 'datetime_strict_matching_segment';
    private const DATETIME_CRITERIA = [
        'start_datetime' => '2022-10-28 12:39:00',
        'end_datetime' => '2022-10-29 12:39:00',
        'timezone' => 'UTC',
        'matches' => [
            'mixed' => 'values',
        ],
    ];

    public function testItShouldKnownAvailableSegmentTypes(): void
    {
        $factory = new IntervalSegmentFactory(SystemClock::create());

        $this->assertSame([
            self::DATETIME_STRICT_MATCHING_SEGMENT,
        ], $factory->types());
    }

    public function testItShouldThrowAnExceptionWithUnknownStrategyTypes(): void
    {
        $this->expectException(InvalidSegmentTypeGiven::class);
        $factory = new IntervalSegmentFactory(SystemClock::create());

        $factory->create(self::SEGMENT_ID, 'unknown_strategy_type', self::DATETIME_CRITERIA);
    }

    public function testItShouldCreateInstancesOfDateTimeIntervalStrictMatchingSegment(): void
    {
        $factory = new IntervalSegmentFactory(SystemClock::create());

        $segment = $factory->create(self::SEGMENT_ID, self::DATETIME_STRICT_MATCHING_SEGMENT, self::DATETIME_CRITERIA);
        $this->assertInstanceOf(IntervalStrictMatchingSegment::class, $segment);
    }
}
