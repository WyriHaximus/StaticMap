<?php

namespace WyriHaximus\StaticMap\Tests;

use WyriHaximus\StaticMap\Tiles;

class TilesTest extends \PHPUnit_Framework_TestCase
{
    public static function getBaseTilesPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR;
    }

    public function testGetTile()
    {
        $tileDirectory = self::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR;
        $Tiles = new Tiles($tileDirectory . '{x}/{y}.png', 'fallback.img');

        $this->assertSame($tileDirectory . '0/0.png', $Tiles->getTile(0, 0));
        $this->assertSame($tileDirectory . '1/0.png', $Tiles->getTile(1, 0));
        $this->assertSame($tileDirectory . '0/1.png', $Tiles->getTile(0, 1));
        $this->assertSame($tileDirectory . '1/1.png', $Tiles->getTile(1, 1));
    }

    public function testGetTileFallback()
    {
        $Tiles = new Tiles('{x}/{y}', 'fallback.img');
        $this->assertSame('fallback.img', $Tiles->getTile(3, 4));
    }

    public function testNoFallback()
    {
        $Tiles = new Tiles('{x}/{y}.png');
        $this->assertSame('0/0.png', $Tiles->getTile(0, 0));
    }
}
