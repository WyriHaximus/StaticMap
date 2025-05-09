<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\LatLng;

final class LatLngTest extends TestCase
{
    /** @return iterable<array<float>> */
    public static function inRangeProvider(): iterable
    {
        for ($lat = -90; $lat <= 90; $lat++) {
            for ($lng = -180; $lng <= 180; $lng++) {
                yield [$lat, $lng];
            }
        }
    }

    #[Test]
    #[DataProvider('inRangeProvider')]
    public function inRange(float $lat, float $lng): void
    {
        $ll = new LatLng($lat, $lng);

        self::assertSame($lat, $ll->lat);
        self::assertSame($lng, $ll->lng);
    }

    /** @return iterable<array<float>> */
    public static function outRangeProvider(): iterable
    {
        foreach (
            [
                -90.1,
                -91,
                90.1,
                91,
            ] as $lat
        ) {
            foreach (
                [
                    -180.1,
                    -181,
                    180.1,
                    181,
                ] as $lng
            ) {
                yield [$lat, $lng];
            }
        }
    }

    #[Test]
    #[DataProvider('outRangeProvider')]
    public function outRange(float $lat, float $lng): void
    {
        $latLng = new LatLng($lat, $lng);

        self::assertGreaterThanOrEqual(-180, $latLng->lng);
        self::assertLessThanOrEqual(180, $latLng->lng);
        self::assertGreaterThanOrEqual(-90, $latLng->lat);
        self::assertLessThanOrEqual(90, $latLng->lat);
    }
}
