<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

class LookIntoRenderer extends \WyriHaximus\StaticMap\Renderer
{
    public function calculateBox()
    {
        return parent::calculateBox();
    }
}

class RendererTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'StaticMapTests_' . md5(time() . uniqid());
        if (!file_exists($this->tmpDir)) {
            @mkdir($this->tmpDir, 0777, true);
        }

        if (!is_writable($this->tmpDir)) {
            $this->markTestSkipped(sprintf('Unable to run the tests as "%s" is not writable.', $this->tmpDir));
        }
    }

    public function tearDown()
    {
        $this->removeDir($this->tmpDir);
    }

    public function imagineProvider()
    {
        return array(
            new \Imagine\Gd\Imagine(),
            //new \Imagine\Imagick\Imagine(), // Disabled for now
        );
    }

    public function testSmallRenderProvider()
    {
        $return = array();
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = array(
                json_decode(file_get_contents(
                    __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderSmallTest.json'
                )),
                $imagine,
            );
        }
        return $return;
    }

    /**
     * @dataProvider testSmallRenderProvider
     */
    public function testSmallRender($checkPoints, $Imagine)
    {
        $Renderer = new \WyriHaximus\StaticMap\Renderer(
            $Imagine,
            1,
            new \Imagine\Image\Box(25, 25),
            new \WyriHaximus\StaticMap\LatLng(0, 0),
            new \WyriHaximus\StaticMap\Tiles(
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );

        $Renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png', 25);
    }

    public function testMediumRenderProvider()
    {
        $return = array();
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = array(
                json_decode(file_get_contents(
                    __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderMediumTest.json'
                )),
                $imagine,
            );
        }
        return $return;
    }

    /**
     * @dataProvider testMediumRenderProvider
     */
    public function testMediumRender($checkPoints, $Imagine)
    {
        $Renderer = new \WyriHaximus\StaticMap\Renderer(
            $Imagine,
            1,
            new \Imagine\Image\Box(256, 256),
            new \WyriHaximus\StaticMap\LatLng(13, 13),
            new \WyriHaximus\StaticMap\Tiles(
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );

        $Renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png', 256);
    }

    public function testBigRenderProvider()
    {
        $return = array();
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = array(
                json_decode(file_get_contents(
                    __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderBigTest.json'
                )),
                $imagine,
            );
        }
        return $return;
    }

    /**
     * @dataProvider testBigRenderProvider
     */
    public function testBigRender($checkPoints, $Imagine)
    {
        $Renderer = new \WyriHaximus\StaticMap\Renderer(
            $Imagine,
            1,
            new \Imagine\Image\Box(345, 345),
            new \WyriHaximus\StaticMap\LatLng(-55, 65),
            new \WyriHaximus\StaticMap\Tiles(
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );

        $Renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png', 345);
    }

    public function testCenterBlipProvider()
    {
        $return = array();
        $imagines = $this->imagineProvider();
        foreach ($imagines as $imagine) {
            $return[] = array(
                json_decode(file_get_contents(
                    __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.json'
                )),
                $imagine,
            );
        }
        return $return;
    }

    /**
     * @dataProvider testCenterBlipProvider
     */
    public function testCenterBlip($checkPoints, $Imagine)
    {
        $Renderer = new \WyriHaximus\StaticMap\Renderer(
            $Imagine,
            1,
            new \Imagine\Image\Box(256, 256),
            new \WyriHaximus\StaticMap\LatLng(13, 13),
            new \WyriHaximus\StaticMap\Tiles(
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'Simple' . DIRECTORY_SEPARATOR . '{x}/{y}.png',
                __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' .
                DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );

        $Renderer->addCenterBlip();

        $Renderer->generate()->save($this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png');

        $this->compareImages($checkPoints, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png', 256);
    }

    /*
    public function _testOutputRender()
    {
    }*/

    private function removeDir($target)
    {
        $fp = opendir($target);
        while (false !== $file = readdir($fp)) {
            if (in_array($file, array('.', '..'))) {
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

    private function compareImages($checkPoints, $fileResult, $size)
    {
        $imSizeResult = getimagesize($fileResult);
        $this->assertEquals($size, $imSizeResult[0]);
        $this->assertEquals($size, $imSizeResult[1]);

        $imResult = imagecreatefrompng($fileResult);

        foreach ($checkPoints as $checkPoint) {
            $rgbResult = @imagecolorat($imResult, $checkPoint->point->x, $checkPoint->point->y);
            $colorsResult = imagecolorsforindex($imResult, $rgbResult);

            $this->assertEquals($checkPoint->colors->red, $colorsResult['red']);
            $this->assertEquals($checkPoint->colors->green, $colorsResult['green']);
            $this->assertEquals($checkPoint->colors->blue, $colorsResult['blue']);
            $this->assertEquals($checkPoint->colors->alpha, $colorsResult['alpha']);
        }

        imagedestroy($imResult);
    }
}
