<?php

declare(strict_types=1);

namespace Pheature\Model\DateTime;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Psr\Clock\ClockInterface;

class IntervalSegmentFactory implements SegmentFactory
{
    private ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;
    }

    public function create(string $segmentId, string $segmentType, array $criteria): Segment
    {
        if (IntervalMatchingSegment::NAME === $segmentType) {
            return new IntervalMatchingSegment($segmentId, $criteria, $this->clock);
        }
        if (IntervalStrictMatchingSegment::NAME === $segmentType) {
            return new IntervalStrictMatchingSegment($segmentId, $criteria, $this->clock);
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }

    public function types(): array
    {
        return [
            IntervalMatchingSegment::NAME,
            IntervalStrictMatchingSegment::NAME,
        ];
    }
}
