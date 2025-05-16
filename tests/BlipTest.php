<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use Imagine\Image\Box;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\Blip;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Point;

use function dirname;

use const DIRECTORY_SEPARATOR;

final class BlipTest extends TestCase
{
    /** @return iterable<array{0: LatLng, 1: Blip}> */
    public static function createProvider(): iterable
    {
        $defaultImage = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'src' .
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

    #[Test]
    #[DataProvider('createProvider')]
    public function create(LatLng $latLng, Blip $result): void
    {
        $resultBlip = Blip::create($latLng);
        static::assertEquals($result, $resultBlip);
    }

    /** @return iterable<array{0: LatLng, 1: string|null, 2: string}> */
    public static function getImageProvider(): iterable
    {
        $defaultImage = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';

        yield [
            new LatLng(71, 111),
            $defaultImage,
            $defaultImage,
        ];

        yield [
            new LatLng(-50, 66),
            TilesTest::getBaseTilesPath() . 'black.jpg',
            TilesTest::getBaseTilesPath() . 'black.jpg',
        ];

        yield [
            new LatLng(-189, 53),
            TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '1' . DIRECTORY_SEPARATOR . '2.png',
            $defaultImage,
        ];

        yield [
            new LatLng(-189, 53),
            null,
            $defaultImage,
        ];
    }

    #[Test]
    #[DataProvider('getImageProvider')]
    public static function getImage(LatLng $latLng, string|null $image, string $result): void
    {
        $resultBlip = Blip::create($latLng, $image);
        static::assertSame($result, $resultBlip->getImage());
    }

    /** @return iterable<array<LatLng>> */
    public static function getLatLngProvider(): iterable
    {
        yield [
            new LatLng(71, 111),
        ];

        yield [
            new LatLng(-50, 66),
        ];

        [
            new LatLng(-189, 53),
        ];
    }

    #[Test]
    #[DataProvider('getLatLngProvider')]
    public function getLatLng(LatLng $latLng): void
    {
        $resultBlip = Blip::create($latLng);
        static::assertSame($latLng, $resultBlip->getLatLng());
    }

    /** @return iterable<array{0: Point, 1: int, 2: LatLng, 3: Box, 4: Point}> */
    public static function calculatePositionProvider(): iterable
    {
        yield [
            new Point(1097, 949),
            3,
            new LatLng(13, 13),
            new Box(666, 666),
            new Point(328, 327),
        ];
    }

    #[Test]
    #[DataProvider('calculatePositionProvider')]
    public function calculatePosition(
        Point $center,
        int $zoom,
        LatLng $latLngBlip,
        Box $size,
        Point $blipPoint,
    ): void {
        $resultBlip = Blip::create($latLngBlip);
        static::assertEquals($blipPoint, $resultBlip->calculatePosition($center, $size, $zoom));
    }
}
