<?php

namespace WyriHaximus\StaticMap\Tests;

use \WyriHaximus\StaticMap\Loader\Simple;
use WyriHaximus\StaticMap\Tiles;

class TilesTest extends \PHPUnit_Framework_TestCase
{
    public static function getBaseTilesPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR;
    }

    public function testGetTileProvider()
    {
        return [
            [0, 0],
            [1, 0],
            [0, 1],
            [1, 1],
        ];
    }

    /**
     * @dataProvider testGetTileProvider
     */
    public function testGetTile($x, $y)
    {
        $tileDirectory = self::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR;
        $Tiles = new Tiles($tileDirectory . '{x}/{y}.png', 'fallback.img');
        $Tiles->setLoader(new Simple());
        $tilePromise = $Tiles->getTile($x, $y);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(function ($fileName) use (&$tile) {
            $tile = $fileName;
        });
        $this->assertSame($tileDirectory . $x . '/' . $y . '.png', $tile);
    }

    public function testGetTileFallback()
    {
        $Tiles = new Tiles('{x}/{y}', 'fallback.img');
        $Tiles->setLoader(new Simple());
        $tilePromise = $Tiles->getTile(3, 4);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(function ($fileName) use (&$tile) {
            $tile = $fileName;
        });
        $this->assertSame('fallback.img', $tile);
    }
    
    public function testNoFallback()
    {
        $Tiles = new Tiles('{x}/{y}.png');
        $Tiles->setLoader(new Simple());
        $tilePromise = $Tiles->getTile(0, 0);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(function ($fileName) use (&$tile) {
            $tile = $fileName;
        });
        $this->assertSame('0/0.png', $tile);
    }
}
