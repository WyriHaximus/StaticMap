<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap;

use WyriHaximus\StaticMap\Loader\Simple;
use WyriHaximus\StaticMap\Tiles;
use WyriHaximus\TestUtilities\TestCase;

use const DIRECTORY_SEPARATOR;

final class TilesTest extends TestCase
{
    public static function getBaseTilesPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return iterable<array<int>>
     */
    public function getTileProvider(): iterable
    {
        yield     [0, 0];
            yield [1, 0];
            yield [0, 1];
            yield [1, 1];
    }

    /**
     * @dataProvider getTileProvider
     */
    public function testGetTile(int $x, int $y): void
    {
        $tileDirectory = self::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR;
        $tiles         = new Tiles($tileDirectory . '{x}/{y}.png', 'fallback.img');
        $tiles->setLoader(new Simple());
        $tilePromise = $tiles->getTile($x, $y);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(
            static function ($fileName) use (&$tile): void {
                $tile = $fileName;
            }
        );
        $this->assertSame($tileDirectory . $x . '/' . $y . '.png', $tile);
    }

    public function testGetTileFallback(): void
    {
        $tiles = new Tiles('{x}/{y}', 'fallback.img');
        $tiles->setLoader(new Simple());
        $tilePromise = $tiles->getTile(3, 4);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(
            static function ($fileName) use (&$tile): void {
                $tile = $fileName;
            }
        );
        $this->assertSame('fallback.img', $tile);
    }

    public function testNoFallback(): void
    {
        $tiles = new Tiles('{x}/{y}.png');
        $tiles->setLoader(new Simple());
        $tilePromise = $tiles->getTile(0, 0);
        $this->assertInstanceOf('\React\Promise\Promise', $tilePromise);
        $tile = null;
        $tilePromise->then(
            static function ($fileName) use (&$tile): void {
                $tile = $fileName;
            }
        );
        $this->assertSame('0/0.png', $tile);
    }
}
