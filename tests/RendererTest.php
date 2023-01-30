<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\StaticMap\Renderer;
use WyriHaximus\StaticMap\Tests\TilesTest;
use WyriHaximus\StaticMap\Tiles;
use WyriHaximus\TestUtilities\TestCase;

use function closedir;
use function file_exists;
use function file_get_contents;
use function getimagesize;
use function imagecolorat;
use function imagecolorsforindex;
use function imagecreatefrompng;
use function imagedestroy;
use function in_array;
use function is_dir;
use function is_writable;
use function json_decode;
use function md5;
use function mkdir;
use function opendir;
use function readdir;
use function rmdir;
use function serialize;
use function sprintf;
use function sys_get_temp_dir;
use function time;
use function uniqid;
use function unlink;

use const DIRECTORY_SEPARATOR;

final class RendererTest extends TestCase
{
    public function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'StaticMapTests_' .
        md5(time() . uniqid()) . md5(serialize($this->getName(true)));
        if (! file_exists($this->tmpDir)) {
            @mkdir($this->tmpDir, 0777, true);
        }

        if (is_writable($this->tmpDir)) {
            return;
        }

        $this->markTestSkipped(sprintf('Unable to run the tests as "%s" is not writable.', $this->tmpDir));
    }

    public function tearDown(): void
    {
        $this->removeDir($this->tmpDir);
    }

    /**
     * @return iterable<array<ImagineInterface>>
     */
    public function imagineProvider(): iterable
    {
        return [
            new Imagine(),
            //new \Imagine\Imagick\Imagine(), // Disabled for now
        ];
    }

    /**
     * @return iterable<array<mixed>>
     */
    public function smallRenderProvider(): iterable
    {
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            yield [
                json_decode(file_get_contents(\WyriHaximus\Tests\StaticMap\TilesTest::getBaseTilesPath() . 'RenderSmallTest.json')),
                $imagine,
            ];
        }
    }

    /**
     * @param array<mixed> $checkPoints
     *
     * @dataProvider smallRenderProvider
     */
    public function testSmallRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(25, 25),
            new LatLng(0, 0),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg'
            )
        );

        $renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png', 25);
    }

    /**
     * @return iterable<array<mixed>>
     */
    public function mediumRenderProvider(): iterable
    {
        $return   = [];
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = [
                json_decode(file_get_contents(TilesTest::getBaseTilesPath() . 'RenderMediumTest.json')),
                $imagine,
            ];
        }

        return $return;
    }

    /**
     * @param array<mixed> $checkPoints
     *
     * @dataProvider mediumRenderProvider
     */
    public function testMediumRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(256, 256),
            new LatLng(13, 13),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg'
            )
        );

        $renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png', 256);
    }

    /**
     * @return iterable<array<mixed>>
     */
    public function bigRenderProvider(): iterable
    {
        $return   = [];
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = [
                json_decode(file_get_contents(TilesTest::getBaseTilesPath() . 'RenderBigTest.json')),
                $imagine,
            ];
        }

        return $return;
    }

    /**
     * @param array<mixed> $checkPoints
     *
     * @dataProvider bigRenderProvider
     */
    public function testBigRender(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(345, 345),
            new LatLng(-55, 65),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg'
            )
        );

        $renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png', 345);
    }

    /**
     * @return iterable<array<mixed>>
     */
    public function centerBlipProvider(): iterable
    {
        $return   = [];
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = [
                json_decode(file_get_contents(TilesTest::getBaseTilesPath() . 'RenderCenterBlipTest.json')),
                $imagine,
            ];
        }

        return $return;
    }

    /**
     * @param array<mixed> $checkPoints
     *
     * @dataProvider centerBlipProvider
     */
    public function testCenterBlip(array $checkPoints, ImagineInterface $imagine): void
    {
        $renderer = new Renderer(
            $imagine,
            1,
            new Box(256, 256),
            new LatLng(13, 13),
            new Tiles(
                TilesTest::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                TilesTest::getBaseTilesPath() . 'black.jpg'
            )
        );

        $renderer->addCenterBlip();

        $renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png', 256);
    }

    private function removeDir(string $target): void
    {
        $fp = opendir($target);
        while (($file = readdir($fp)) !== false) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            if (is_dir($target . DIRECTORY_SEPARATOR . $file)) {
                self::removeDir($target . DIRECTORY_SEPARATOR . $file);
            } else {
                unlink($target . DIRECTORY_SEPARATOR . $file);
            }
        }

        closedir($fp);
        rmdir($target);
    }

    /**
     * @param array<object> $checkPoints
     * @param array<int>    $size
     */
    private function compareImages(array $checkPoints, string $fileResult, array $size): void
    {
        $imSizeResult = getimagesize($fileResult);
        $this->assertEquals($size, $imSizeResult[0]);
        $this->assertEquals($size, $imSizeResult[1]);

        $imResult = imagecreatefrompng($fileResult);

        foreach ($checkPoints as $checkPoint) {
            $rgbResult    = @imagecolorat($imResult, $checkPoint->point->x, $checkPoint->point->y);
            $colorsResult = imagecolorsforindex($imResult, $rgbResult);

            $this->assertEquals($checkPoint->colors->red, $colorsResult['red']);
            $this->assertEquals($checkPoint->colors->green, $colorsResult['green']);
            $this->assertEquals($checkPoint->colors->blue, $colorsResult['blue']);
            $this->assertEquals($checkPoint->colors->alpha, $colorsResult['alpha']);
        }

        imagedestroy($imResult);
    }
}
