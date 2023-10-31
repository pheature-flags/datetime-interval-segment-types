<?php

declare(strict_types=1);

namespace Pheature\Model\DateTime;

use Pheature\Core\Toggle\Read\SegmentFactory;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

class IntervalSegmentFactoryFactory
{
    public function __invoke(ContainerInterface $container): SegmentFactory
    {
        $clock = $container->get(ClockInterface::class);
        Assert::isInstanceOf($clock, ClockInterface::class);

        return new IntervalSegmentFactory(
            $clock
        );
    }
}
