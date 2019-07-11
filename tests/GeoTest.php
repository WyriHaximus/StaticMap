<?php

namespace WyriHaximus\StaticMap\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\Point;
use WyriHaximus\StaticMap\LatLng;

class GeoTest extends TestCase
{
    public function testCalculateBox()
    {
        $box = \WyriHaximus\StaticMap\Geo::calculateBox(
            new \Imagine\Image\Box(25, 25),
            \WyriHaximus\StaticMap\Geo::calculatePoint(
                new LatLng(71, 111),
                3
            )
        );

        $this->assertTrue($box['tiles']['start'] instanceof \WyriHaximus\StaticMap\Point);
        $this->assertEquals(6, $box['tiles']['start']->getX());
        $this->assertEquals(1, $box['tiles']['start']->getY());

        $this->assertTrue($box['tiles']['stop'] instanceof \WyriHaximus\StaticMap\Point);
        $this->assertEquals(9, $box['tiles']['stop']->getX());
        $this->assertEquals(4, $box['tiles']['stop']->getY());

        $this->assertTrue($box['crop'] instanceof \WyriHaximus\StaticMap\Point);
        $this->assertEquals(363, $box['crop']->getX());
        $this->assertEquals(429, $box['crop']->getY());

        $this->assertTrue($box['base'] instanceof \Imagine\Image\Box);
        $this->assertEquals(768, $box['base']->getWidth());
        $this->assertEquals(768, $box['base']->getHeight());
    }

    public function calculatePointProvider()
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
        \WyriHaximus\StaticMap\LatLng $latLon,
        $zoom,
        \WyriHaximus\StaticMap\Point $point
    ) {
        $resultPoint = \WyriHaximus\StaticMap\Geo::calculatePoint($latLon, $zoom);
        $this->assertEquals($point, $resultPoint);
    }
}
