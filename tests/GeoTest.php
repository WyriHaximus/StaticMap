<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

class GeoTest extends \PHPUnit_Framework_TestCase
{
    public function testCalculateBox()
    {
        $box = \WyriHaximus\StaticMap\Geo::calculateBox(
            new \Imagine\Image\Box(25, 25),
            \WyriHaximus\StaticMap\Geo::calculatePoint(
                new \WyriHaximus\StaticMap\LatLng(71, 111),
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

    public function testCalculatePointProvider()
    {
        return array(
            // #1
            array(
                new \WyriHaximus\StaticMap\LatLng(71, 111),
                3,
                new \WyriHaximus\StaticMap\Point(1655, 441.29647761708),
            ),
            // #2
            array(
                new \WyriHaximus\StaticMap\LatLng(-50, 66),
                1,
                new \WyriHaximus\StaticMap\Point(349, 338.35787539394),
            ),
            // #3
            array(
                new \WyriHaximus\StaticMap\LatLng(-189, 53),
                7,
                new \WyriHaximus\StaticMap\Point(21208, 16384),
            ),
        );
    }

    /**
     * @dataProvider testCalculatePointProvider
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
