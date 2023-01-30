<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap;

use Imagine\Image\Box;
use WyriHaximus\StaticMap\Blip;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Point;
use WyriHaximus\TestUtilities\TestCase;

use function dirname;

use const DIRECTORY_SEPARATOR;

final class BlipTest extends TestCase
{
    /**
     * @return iterable<array<LatLng, Blip>>
     */
    public function createProvider(): iterable
    {
        $defaultImage = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' .
        DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';

            yield [
                new LatLng(71, 111),
                new Blip(new LatLng(71, 111), $defaultImage),
            ];

            yield [
                new LatLng(-50, 66),
                new Blip(new LatLng(-50, 66), $defaultImage),
            ];

            yield [
                new LatLng(-189, 53),
                new Blip(new LatLng(-189, 53), $defaultImage),
            ];
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreate(LatLng $latLng, Blip $result): void
    {
        $resultBlip = Blip::create($latLng);
        $this->assertEquals($result, $resultBlip);
    }

    /**
     * @return iterable<array<LatLng, string>>
     */
    public function getImageProvider(): iterable
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
     * @dataProvider getImageProvider
     */
    public function testGetImage(LatLng $latLng, string $image, Blip $result): void
    {
        $resultBlip = Blip::create($latLng, $image);
        $this->assertEquals($result, $resultBlip->getImage());
    }

    /**
     * @return iterable<array<LatLng>>
     */
    public function getLatLngProvider(): iterable
    {
        yield            [
            new LatLng(71, 111),
        ];

            yield [
                new LatLng(-50, 66),
            ];

            yield [
                new LatLng(-189, 53),
            ];
    }

    /**
     * @dataProvider getLatLngProvider
     */
    public function testGetLatLng(LatLng $latLng): void
    {
        $resultBlip = Blip::create($latLng);
        $this->assertEquals($latLng, $resultBlip->getLatLng());
    }

    /**
     * @return iterable<array<Point, int, LatLng, Box>>
     */
    public function calculatePositionProvider(): iterable
    {
            yield [
                new Point(1097, 949.40161077744),
                3,
                new LatLng(13, 13),
                new Box(12, 12),
                new Point(0.5, 0.5),
            ];
    }

    /**
     * @dataProvider calculatePositionProvider
     */
    public function testCalculatePosition(
        Point $center,
        int $zoom,
        LatLng $latLngBlip,
        Box $size,
        Point $blipPoint
    ): void {
        $resultBlip = Blip::create($latLngBlip);
        $this->assertEquals($blipPoint, $resultBlip->calculatePosition($center, $size, $zoom));
    }
}
