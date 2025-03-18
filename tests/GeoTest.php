<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use Imagine\Image\Box;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\Geo;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Point;

final class GeoTest extends TestCase
{
    #[Test]
    public function calculateBox(): void
    {
        $box = Geo::calculateBox(
            new Box(25, 25),
            Geo::calculatePoint(
                new LatLng(71, 111),
                3,
            ),
        );

        static::assertEquals(6, $box->tiles->start->getX());
        static::assertEquals(1, $box->tiles->start->getY());

        static::assertEquals(9, $box->tiles->stop->getX());
        static::assertEquals(4, $box->tiles->stop->getY());

        static::assertEquals(363, $box->crop->getX());
        static::assertEquals(429, $box->crop->getY());

        static::assertEquals(768, $box->base->getWidth());
        static::assertEquals(768, $box->base->getHeight());
    }

    /** @return iterable<array{0: LatLng, 1: int, 2: Point}> */
    public static function calculatePointProvider(): iterable
    {
        // #1
        yield [
            new LatLng(71, 111),
            3,
            new Point(1655, 441),
        ];

        // #2
        yield [
            new LatLng(-50, 66),
            1,
            new Point(349, 338),
        ];

        // #3
        yield [
            new LatLng(0, 53),
            7,
            new Point(21208, 16384),
        ];
    }

    #[Test]
    #[DataProvider('calculatePointProvider')]
    public function calculatePoint(
        LatLng $latLon,
        int $zoom,
        Point $point,
    ): void {
        $resultPoint = Geo::calculatePoint($latLon, $zoom);
        static::assertEquals($point, $resultPoint);
    }
}
