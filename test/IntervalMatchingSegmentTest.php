<?php

declare(strict_types=1);

namespace Pheature\Test\Model\DateTime;

use Beste\Clock\FrozenClock;
use DateTimeImmutable;
use DateTimeZone;
use Generator;
use InvalidArgumentException;
use Pheature\Model\DateTime\IntervalMatchingSegment;
use PHPUnit\Framework\TestCase;

class IntervalMatchingSegmentTest extends TestCase
{
    private const SEGMENT_ID = 'a_segment_id';

    /** @dataProvider invalidPayloads */
    public function testItShouldThrowInvalidArgumentExceptionWithInvalidStructure(array $criteria): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(0);
        $now = new DateTimeImmutable();
        new IntervalMatchingSegment(self::SEGMENT_ID, $criteria, FrozenClock::at($now));
    }


    /** @dataProvider nonMatchingPayloads */
    public function testItShouldNotMatch(DateTimeImmutable $now, array $criteria, array $payload): void
    {
        $segment = new IntervalMatchingSegment(self::SEGMENT_ID, $criteria, FrozenClock::at($now));

        $this->assertFalse($segment->match($payload));
        $this->assertSame(self::SEGMENT_ID, $segment->id());
        $this->assertSame('datetime_matching_segment', $segment->type());
        $this->assertSame($criteria, $segment->criteria());
        $this->assertSame([
            'id' => self::SEGMENT_ID,
            'type' => 'datetime_matching_segment',
            'criteria' => $criteria,
        ], $segment->jsonSerialize());
    }

    /** @dataProvider matchingPayloads */
    public function testItShouldMatch(DateTimeImmutable $now, array $criteria, array $payload): void
    {
        $segment = new IntervalMatchingSegment(self::SEGMENT_ID, $criteria, FrozenClock::at($now));

        $this->assertTrue($segment->match($payload));
        $this->assertSame(self::SEGMENT_ID, $segment->id());
        $this->assertSame('datetime_matching_segment', $segment->type());
        $this->assertSame($criteria, $segment->criteria());
        $this->assertSame([
            'id' => self::SEGMENT_ID,
            'type' => 'datetime_matching_segment',
            'criteria' => $criteria,
        ], $segment->jsonSerialize());
    }

    public function invalidPayloads(): Generator
    {
        yield 'Missing start date time' => [
            'criteria' => [
                'end_datetime' => '2022-10-28 11:30:00',
                'timezone' => 'UTC',
            ]
        ];
        yield 'Missing end date time' => [
            'criteria' => [
                'start_datetime' => '2022-10-28 11:30:00',
                'timezone' => 'UTC',
            ]
        ];
        yield 'Missing timezone' => [
            'criteria' => [
                'start_datetime' => '2022-10-27 11:30:00',
                'end_datetime' => '2022-10-28 11:30:00',
            ]
        ];
    }


    public function nonMatchingPayloads(): Generator
    {
        yield 'Before enable' => [
            'now' => new DateTimeImmutable('2022-10-27 11:30:00'),
            'criteria' => [
                'start_datetime' => '2022-10-27 11:30:01',
                'end_datetime' => '2022-10-28 11:30:00',
                'timezone' => 'UTC',
                'matches' => [],
            ],
            'payload' => [
                'location' => 'Milan'
            ]
        ];
        yield 'After disable' => [
            'now' => new DateTimeImmutable('2022-10-28 11:30:01'),
            'criteria' => [
                'start_datetime' => '2022-10-27 11:30:01',
                'end_datetime' => '2022-10-28 11:30:00',
                'timezone' => 'UTC',
                'matches' => [],
            ],
            'payload' => [
                'location' => 'Milan'
            ]
        ];
    }

    public function matchingPayloads(): Generator
    {
        yield 'Matches in time' => [
            'now' => new DateTimeImmutable('2022-10-27 11:30:00', new DateTimeZone('GMT')),
            'criteria' => [
                'start_datetime' => '2022-10-27 11:30:00',
                'end_datetime' => '2022-10-28 11:30:00',
                'timezone' => 'GMT',
                'matches' => [],
            ],
            'payload' => [
                'location' => 'Milan'
            ]
        ];
    }
}
