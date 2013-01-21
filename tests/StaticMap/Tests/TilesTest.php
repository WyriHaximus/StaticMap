<?php

namespace StaticMap\Tests;

class TilesTest extends \PHPUnit_Framework_TestCase {
    
    public function testGetTile() {
        $tileDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR;
        $Tiles = new \StaticMap\Tiles($tileDirectory . '{x}/{y}.png', 'fallback.img');
        
        $this->assertSame($tileDirectory . '0/0.png', $Tiles->getTile(0, 0));
        $this->assertSame($tileDirectory . '1/0.png', $Tiles->getTile(1, 0));
        $this->assertSame($tileDirectory . '0/1.png', $Tiles->getTile(0, 1));
        $this->assertSame($tileDirectory . '1/1.png', $Tiles->getTile(1, 1));
    }
    
    public function testGetTileFallback() {
        $Tiles = new \StaticMap\Tiles('{x}/{y}', 'fallback.img');
        $this->assertSame('fallback.img', $Tiles->getTile(3, 4));
    }
    
}