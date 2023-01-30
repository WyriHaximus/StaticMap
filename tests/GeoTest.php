<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap;

use Imagine\Image\Box;
use WyriHaximus\StaticMap\Geo;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Point;
use WyriHaximus\TestUtilities\TestCase;

final class GeoTest extends TestCase
{
    public function testCalculateBox(): void
    {
        $box = Geo::calculateBox(
            new Box(25, 25),
            Geo::calculatePoint(
                new LatLng(71, 111),
                3
            )
        );

        $this->assertTrue($box['tiles']['start'] instanceof Point);
        $this->assertEquals(6, $box['tiles']['start']->getX());
        $this->assertEquals(1, $box['tiles']['start']->getY());

        $this->assertTrue($box['tiles']['stop'] instanceof Point);
        $this->assertEquals(9, $box['tiles']['stop']->getX());
        $this->assertEquals(4, $box['tiles']['stop']->getY());

        $this->assertTrue($box['crop'] instanceof Point);
        $this->assertEquals(363, $box['crop']->getX());
        $this->assertEquals(429, $box['crop']->getY());

        $this->assertTrue($box['base'] instanceof Box);
        $this->assertEquals(768, $box['base']->getWidth());
        $this->assertEquals(768, $box['base']->getHeight());
    }

    /**
     * @return array<array<LatLng, int, Point>>
     */
    public function calculatePointProvider(): array
    {
        return [
            // #1
            [
                new LatLng(71, 111),
                3,
                new Point(1655, 441.29647761708),
            ],
            // #2
            [
                new LatLng(-50, 66),
                1,
                new Point(349, 338.35787539394),
            ],
            // #3
            [
                new LatLng(-189, 53),
                7,
                new Point(21208, 16384),
            ],
        ];
    }

    /**
     * @dataProvider calculatePointProvider
     */
    public function testCalculatePoint(
        LatLng $latLon,
        int $zoom,
        Point $point
    ): void {
        $resultPoint = Geo::calculatePoint($latLon, $zoom);
        $this->assertEquals($point, $resultPoint);
    }
}
