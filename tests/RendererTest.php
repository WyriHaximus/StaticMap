<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Renderer;
use WyriHaximus\StaticMap\Tiles;

use function file_get_contents;
use function getimagesize;
use function imagecolorat;
use function imagecolorsforindex;
use function imagecreatefrompng;
use function imagedestroy;
use function json_decode;

use const DIRECTORY_SEPARATOR;

final class RendererTest extends AsyncTestCase
{
    /** @return iterable<ImagineInterface> */
    public static function imagineProvider(): iterable
    {
        yield new Imagine();
        //yield new \Imagine\Imagick\Imagine(); // Disabled for now
    }

    /** @return iterable<array{0: array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}>, 1: ImagineInterface}> */
    public static function smallRenderProvider(): iterable
    {
        $imagines = static::imagineProvider();
        foreach ($imagines as $imagine) {
            $contents = file_get_contents(TilesTest::getBaseTilesPath() . 'RenderSmallTest.json');
            if ($contents === false) {
                throw new RuntimeException('Failed to open RenderSmallTest.json');
            }

            /** @var array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $json */
            $json = json_decode($contents, true);

            yield [
                $json,
                $imagine,
            ];
        }
    }

    /** @param array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $checkPoints */
    #[Test]
    #[DataProvider('smallRenderProvider')]
    public function testSmallRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(25, 25),
            new LatLng(0, 0),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg',
            ),
        );

        $renderer->generate()->save($this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderSmallTest.png');

        $this->compareImages($checkPoints, $this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderSmallTest.png', 25);
    }

    /** @return iterable<array{0: array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}>, 1: ImagineInterface}> */
    public static function mediumRenderProvider(): iterable
    {
        $imagines = static::imagineProvider();
        foreach ($imagines as $imagine) {
            $contents = file_get_contents(TilesTest::getBaseTilesPath() . 'RenderMediumTest.json');
            if ($contents === false) {
                throw new RuntimeException('Failed to open RenderMediumTest.json');
            }

            /** @var array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $json */
            $json = json_decode($contents, true);

            yield [
                $json,
                $imagine,
            ];
        }
    }

    /** @param array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $checkPoints */
    #[Test]
    #[DataProvider('mediumRenderProvider')]
    public function testMediumRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(256, 256),
            new LatLng(13, 13),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg',
            ),
        );

        $renderer->generate()->save($this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderMediumTest.png');

        $this->compareImages($checkPoints, $this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderMediumTest.png', 256);
    }

    /** @return iterable<array{0: array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}>, 1: ImagineInterface}> */
    public static function bigRenderProvider(): iterable
    {
        $imagines = static::imagineProvider();
        foreach ($imagines as $imagine) {
            $contents = file_get_contents(TilesTest::getBaseTilesPath() . 'RenderBigTest.json');
            if ($contents === false) {
                throw new RuntimeException('Failed to open RenderBigTest.json');
            }

            /** @var array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $json */
            $json = json_decode($contents, true);

            yield [
                $json,
                $imagine,
            ];
        }
    }

    /** @param array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $checkPoints */
    #[Test]
    #[DataProvider('bigRenderProvider')]
    public function testBigRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(345, 345),
            new LatLng(-55, 65),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg',
            ),
        );

        $renderer->generate()->save($this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderBigTest.png');

        $this->compareImages($checkPoints, $this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderBigTest.png', 345);
    }

    /** @return iterable<array{0: array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}>, 1: ImagineInterface}> */
    public static function centerBlipProvider(): iterable
    {
        $imagines = static::imagineProvider();
        foreach ($imagines as $imagine) {
            $contents = file_get_contents(TilesTest::getBaseTilesPath() . 'RenderCenterBlipTest.json');
            if ($contents === false) {
                throw new RuntimeException('Failed to open RenderCenterBlipTest.json');
            }

            /** @var array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $json */
            $json = json_decode($contents, true);

            yield [
                $json,
                $imagine,
            ];
        }
    }

    /** @param array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $checkPoints */
    #[Test]
    #[DataProvider('centerBlipProvider')]
    public function testCenterBlip(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(256, 256),
            new LatLng(13, 13),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg',
            ),
        );

        $renderer->addCenterBlip();

        $renderer->generate()->save($this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png');

        $this->compareImages($checkPoints, $this->getTmpDir() . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png', 256);
    }

    /*
    public function _testOutputRender()
    {
    }*/

    /** @param array<array{point: array{x: int, y: int}, colors: array{red: int, green: int, blue: int, alpha: int}}> $checkPoints */
    private function compareImages(array $checkPoints, string $fileResult, int $size): void
    {
        $imSizeResult = getimagesize($fileResult);
        if ($imSizeResult === false) {
            throw new RuntimeException('Failed to open image file');
        }

        static::assertEquals($size, $imSizeResult[0]);
        static::assertEquals($size, $imSizeResult[1]);

        $imResult = imagecreatefrompng($fileResult);
        if ($imResult === false) {
            throw new RuntimeException('Failed to open image file');
        }

        foreach ($checkPoints as $checkPoint) {
            $rgbResult = imagecolorat($imResult, $checkPoint['point']['x'], $checkPoint['point']['y']);
            if ($rgbResult === false) {
                throw new RuntimeException('Failed to get colour at point');
            }

            $colorsResult = imagecolorsforindex($imResult, $rgbResult);

            static::assertEquals($checkPoint['colors']['red'], $colorsResult['red']);
            static::assertEquals($checkPoint['colors']['green'], $colorsResult['green']);
            static::assertEquals($checkPoint['colors']['blue'], $colorsResult['blue']);
            static::assertEquals($checkPoint['colors']['alpha'], $colorsResult['alpha']);
        }

        imagedestroy($imResult);
    }
}
