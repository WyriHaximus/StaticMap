<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use React\Promise\Promise;
use WyriHaximus\StaticMap\Loader\Simple;
use WyriHaximus\StaticMap\Tiles;

use function React\Async\await;

use const DIRECTORY_SEPARATOR;

final class TilesTest extends TestCase
{
    public static function getBaseTilesPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR;
    }

    /** @return iterable<array<int>> */
    public static function getTileProvider(): iterable
    {
        yield [0, 0];
        yield [1, 0];
        yield [0, 1];
        yield [1, 1];
    }

    #[Test]
    #[DataProvider('getTileProvider')]
    public function getTile(int $x, int $y): void
    {
        $tileDirectory = self::getBaseTilesPath() . 'Simple' . DIRECTORY_SEPARATOR;
        $tiles         = new Tiles($tileDirectory . '{x}/{y}.png', 'fallback.img');
        $tiles->setLoader(new Simple());
        $tile = await($tiles->getTile($x, $y));
        static::assertSame($tileDirectory . $x . '/' . $y . '.png', $tile);
    }

    #[Test]
    public function getTileFallback(): void
    {
        $tiles = new Tiles('{x}/{y}', 'fallback.img');
        $tiles->setLoader(new Simple());
        $tilePromise = $tiles->getTile(3, 4);
        static::assertInstanceOf(Promise::class, $tilePromise);
        /** @var ?string $tile */
        $tile = null;
        $tilePromise->then(
            /** @phpstan-ignore argument.type */
            static function (string $fileName) use (&$tile): void {
                $tile = $fileName;
            },
        );
        static::assertSame('fallback.img', $tile);
    }

    #[Test]
    public function noFallback(): void
    {
        $tiles = new Tiles('{x}/{y}.png');
        $tiles->setLoader(new Simple());
        $tile = await($tiles->getTile(0, 0));
        static::assertSame('0/0.png', $tile);
    }
}
