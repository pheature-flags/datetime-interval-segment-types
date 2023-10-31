<?php

namespace Pheature\Model\DateTime;

use Pheature\Core\Toggle\Read\Segment;
use Psr\Clock\ClockInterface;

class IntervalMatchingSegment implements Segment
{
    public const NAME = 'datetime_matching_segment';
    private string $id;
    private IntervalCriteria $criteria;
    private ClockInterface $clock;

    /** @param array<mixed> $criteria */
    public function __construct(string $segmentId, array $criteria, ClockInterface $clock)
    {
        $this->id = $segmentId;
        $this->criteria = IntervalCriteria::fromRawCriteria($criteria);
        $this->clock = $clock;
    }

    public function match(array $payload): bool
    {
        if ($this->criteria->isOnTime($this->clock->now())) {
            return true;
        }

        return false;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::NAME;
    }

    public function criteria(): array
    {
        return $this->criteria->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => self::NAME,
            'criteria' => $this->criteria->toArray(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
