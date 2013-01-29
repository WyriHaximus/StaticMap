<?php

namespace StaticMap\Tests;

class LookIntoRenderer extends \StaticMap\Renderer
{
    public function calculateBox()
    {
        return parent::calculateBox();
    }

}

abstract class AbstractRendererTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'StaticMapTests';
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

    public function _testInheritance()
    {
        $classImplements = class_implements($this->Renderer);
        $this->assertTrue(isset($classImplements['StaticMap\RendererInterface']));
    }

    public function testCalculateBox()
    {
        $Renderer = new LookIntoRenderer(
            3,
            new \StaticMap\Size(25, 25),
            new \StaticMap\LatLng(71, 111),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );

        $box = $Renderer->calculateBox();

        $this->assertTrue($box['tiles']['start'] instanceof \StaticMap\Size);
        $this->assertEquals(5, $box['tiles']['start']->getWidth());
        $this->assertEquals(0, $box['tiles']['start']->getHeight());

        $this->assertTrue($box['tiles']['stop'] instanceof \StaticMap\Size);
        $this->assertEquals(9, $box['tiles']['stop']->getWidth());
        $this->assertEquals(4, $box['tiles']['stop']->getHeight());

        $this->assertTrue($box['crop'] instanceof \StaticMap\Size);
        $this->assertEquals(363, $box['crop']->getWidth());
        $this->assertEquals(429, $box['crop']->getHeight());

        $this->assertTrue($box['base'] instanceof \StaticMap\Size);
        $this->assertEquals(768, $box['base']->getWidth());
        $this->assertEquals(768, $box['base']->getHeight());
    }

    public function testSmallRender()
    {
        $RendererClass = new \ReflectionClass($this->RendererClass);
        $Renderer = $RendererClass->newInstance(
            1,
            new \StaticMap\Size(25, 25),
            new \StaticMap\LatLng(0, 0),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );

        $Renderer->generate();

        $Renderer->save('png', 9, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png');

        $Renderer->destroy();

        $this->compareImages(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderSmallTest.png', $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderSmallTest.png', 25);
    }

    public function testMediumRender()
    {
        $RendererClass = new \ReflectionClass($this->RendererClass);
        $Renderer = $RendererClass->newInstance(
            1,
            new \StaticMap\Size(256, 256),
            new \StaticMap\LatLng(13, 13),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );

        $Renderer->generate();

        $Renderer->save('png', 9, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png');

        $Renderer->destroy();

        $this->compareImages(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderMediumTest.png', $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderMediumTest.png', 256);
    }

    public function testBigRender()
    {
        $RendererClass = new \ReflectionClass($this->RendererClass);
        $Renderer = $RendererClass->newInstance(
            1,
            new \StaticMap\Size(345, 345),
            new \StaticMap\LatLng(-55, 65),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );

        $Renderer->generate();

        $Renderer->save('png', 9, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png');

        $Renderer->destroy();

        $this->compareImages(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderBigTest.png', $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderBigTest.png', 345);
    }
    
    public function testCenterBlip()
    {
        $RendererClass = new \ReflectionClass($this->RendererClass);
        $Renderer = $RendererClass->newInstance(
            1,
            new \StaticMap\Size(256, 256),
            new \StaticMap\LatLng(13, 13),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );
        
        $Renderer->addCenterBlip();

        $Renderer->generate();

        $Renderer->save('png', 9, $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png');

        $Renderer->destroy();

        $this->compareImages(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png', $this->tmpDir . DIRECTORY_SEPARATOR . 'RenderCenterBlipTest.png', 256);
    }

    public function _testSmallJpegRender()
    {
    }

    public function _testOutputRender()
    {
    }

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

    private function compareImages($fileGood, $fileResult, $size)
    {
        $imSizeResult = getimagesize($fileResult);
        
        $this->assertEquals($size, $imSizeResult[0]);
        $this->assertEquals($size, $imSizeResult[1]);
        
        $imGood = imagecreatefrompng($fileGood);
        $imResult = imagecreatefrompng($fileResult);

        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $rgbGood = @imagecolorat($imGood, $i, $j);
                $colorsGood = imagecolorsforindex($imGood, $rgbGood);

                $rgbResult = @imagecolorat($imResult, $i, $j);
                $colorsResult = imagecolorsforindex($imResult, $rgbResult);

                $this->assertEquals($colorsGood['red'], $colorsResult['red']);
                $this->assertEquals($colorsGood['green'], $colorsResult['green']);
                $this->assertEquals($colorsGood['blue'], $colorsResult['blue']);
                $this->assertEquals($colorsGood['alpha'], $colorsResult['alpha']);
            }
        }

        imagedestroy($imGood);
        imagedestroy($imResult);
    }

}
