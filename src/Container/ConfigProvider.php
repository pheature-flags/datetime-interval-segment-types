<?php

declare(strict_types=1);

namespace Pheature\Model\DateTime\Container;

use Pheature\Model\DateTime\IntervalSegmentFactory;
use Pheature\Model\DateTime\IntervalSegmentFactoryFactory;

final class ConfigProvider
{
    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    IntervalSegmentFactory::class => IntervalSegmentFactoryFactory::class,
                ],
            ],
        ];
    }
}
