<?php

declare(strict_types=1);

namespace Pheature\Test\Model\DateTime\Container;

use Pheature\Model\DateTime\IntervalSegmentFactory;
use Pheature\Model\DateTime\IntervalSegmentFactoryFactory;
use Pheature\Model\DateTime\Container\ConfigProvider;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                IntervalSegmentFactory::class => IntervalSegmentFactoryFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        $this->assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
