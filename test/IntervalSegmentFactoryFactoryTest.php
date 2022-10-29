<?php

declare(strict_types=1);

namespace Pheature\Test\Model\DateTime;

use Beste\Clock\SystemClock;
use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Model\DateTime\IntervalSegmentFactory;
use Pheature\Model\DateTime\IntervalSegmentFactoryFactory;
use Pheature\Model\DateTime\IntervalStrictMatchingSegment;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use StellaMaris\Clock\ClockInterface;

class IntervalSegmentFactoryFactoryTest extends TestCase
{
    public function testItShouldCreateADateTimeIntervalSegmentTypeFactory(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ClockInterface::class)
            ->willReturn(SystemClock::create());
        $segmentFactoryFactory = new IntervalSegmentFactoryFactory();

        $segmentFactory = $segmentFactoryFactory->__invoke($container);
        $this->assertInstanceOf(IntervalSegmentFactory::class, $segmentFactory);
    }
}
