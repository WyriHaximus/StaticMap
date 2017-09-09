<?php

namespace WyriHaximus\StaticMap\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\Blip;
use WyriHaximus\StaticMap\Point;
use WyriHaximus\StaticMap\LatLng;

class BlipTest extends TestCase
{

    public function testCreateProvider()
    {
        $defaultImage = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' .
        DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
        return [
            [
                new LatLng(71, 111),
                new Blip(new LatLng(71, 111), $defaultImage),
            ],
            [
                new LatLng(-50, 66),
                new Blip(new LatLng(-50, 66), $defaultImage),
            ],
            [
                new LatLng(-189, 53),
                new Blip(new LatLng(-189, 53), $defaultImage),
            ],
        ];
    }

    /**
     * @dataProvider testCreateProvider
     */
    public function testCreate(\WyriHaximus\StaticMap\LatLng $latLng, $result)
    {
        $resultBlip = \WyriHaximus\StaticMap\Blip::create($latLng);
        $this->assertEquals($result, $resultBlip);
    }

    public function testGetImageProvider()
    {
        $defaultImage = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' .
        DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';

        return [
            [
                new LatLng(71, 111),
                $defaultImage,
                $defaultImage,
            ],
            [
                new LatLng(-50, 66),
                TilesTest::getBaseTilesPath() . 'black.jpg',
                TilesTest::getBaseTilesPath() . 'black.jpg',
            ],
            [
                new LatLng(-189, 53),
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '1' . DIRECTORY_SEPARATOR . '2.png',
                $defaultImage,
            ],
            [
                new LatLng(-189, 53),
                null,
                $defaultImage,
            ],
        ];
    }

    /**
     * @dataProvider testGetImageProvider
     */
    public function testGetImage(\WyriHaximus\StaticMap\LatLng $latLng, $image, $result)
    {
        $resultBlip = \WyriHaximus\StaticMap\Blip::create($latLng, $image);
        $this->assertEquals($result, $resultBlip->getImage());
    }

    public function testGetLatLngProvider()
    {
        return [
            [
                new LatLng(71, 111),
            ],
            [
                new LatLng(-50, 66),
            ],
            [
                new LatLng(-189, 53),
            ],
        ];
    }

    /**
     * @dataProvider testGetLatLngProvider
     */
    public function testGetLatLng(\WyriHaximus\StaticMap\LatLng $latLng)
    {
        $resultBlip = \WyriHaximus\StaticMap\Blip::create($latLng);
        $this->assertEquals($latLng, $resultBlip->getLatLng());
    }

    public function testCalculatePositionProvider()
    {
        return [
            [
                new Point(1097, 949.40161077744),
                3,
                new LatLng(13, 13),
                new \Imagine\Image\Box(12, 12),
                new Point(0.5, 0.5),
            ],
        ];
    }

    /**
     * @dataProvider testCalculatePositionProvider
     */
    public function testCalculatePosition(
        \WyriHaximus\StaticMap\Point $center,
        $zoom,
        \WyriHaximus\StaticMap\LatLng $latLngBlip,
        \Imagine\Image\Box $size,
        \WyriHaximus\StaticMap\Point $blipPoint
    ) {
        $resultBlip = \WyriHaximus\StaticMap\Blip::create($latLngBlip);
        $this->assertEquals($blipPoint, $resultBlip->calculatePosition($center, $size, $zoom));
    }
}
