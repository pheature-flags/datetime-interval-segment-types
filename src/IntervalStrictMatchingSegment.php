<?php

declare(strict_types=1);

namespace Pheature\Model\DateTime;

use Pheature\Core\Toggle\Read\Segment;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Psr\Clock\ClockInterface;

/**
 * @psalm-import-type Criteria from IntervalCriteria
 */
final class IntervalStrictMatchingSegment implements Segment
{
    public const NAME = 'datetime_strict_matching_segment';
    private string $id;
    private IntervalCriteria $criteria;
    private StrictMatchingSegment $strictMatchingSegment;
    private IntervalMatchingSegment $intervalMatchingSegment;

    /** @param array<mixed> $criteria */
    public function __construct(string $id, array $criteria, ClockInterface $clock)
    {
        $this->id = $id;
        $this->criteria = IntervalCriteria::fromRawCriteria($criteria);
        $this->intervalMatchingSegment = new IntervalMatchingSegment($id, $criteria, $clock);
        $this->strictMatchingSegment = new StrictMatchingSegment($id, $this->criteria->matches());
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

    public function match(array $payload): bool
    {
        if (false === $this->intervalMatchingSegment->match($payload)) {
            return false;
        }

        return $this->strictMatchingSegment->match($payload);
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
