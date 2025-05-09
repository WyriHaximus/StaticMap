<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\Point;

#[CoversMethod(Point::class, 'getX')]
#[CoversMethod(Point::class, 'getY')]
#[CoversMethod(Point::class, 'in')]
#[CoversMethod(Point::class, 'move')]
final class PointTest extends TestCase
{
    #[Test]
    #[DataProvider('getCoordinates')]
    public function shouldAssignXYCoordinates(int $x, int $y, BoxInterface $box, bool $expected): void
    {
        $coordinate = new Point($x, $y);

        static::assertSame($x, $coordinate->x);
        static::assertSame($y, $coordinate->y);

        static::assertSame($expected, $coordinate->in($box));
    }

    /** @return iterable<array{0: int, 1: int, 2: Box, 3: bool}> */
    public static function getCoordinates(): iterable
    {
        yield [0, 0, new Box(5, 5), true];
        yield [5, 15, new Box(5, 5), false];
        yield [10, 23, new Box(10, 10), false];
        yield [42, 30, new Box(50, 50), true];
        yield [81, 16, new Box(50, 10), false];
    }

    #[Test]
    #[DataProvider('getMoves')]
    public function shouldMoveByGivenAmount(int $x, int $y, int $move, int $x1, int $y1): void
    {
        $point = new Point($x, $y);
        $shift = $point->move($move);

        static::assertInstanceOf(Point::class, $shift);
        static::assertSame($x1, $shift->x);
        static::assertSame($y1, $shift->y);
    }

    /** @return iterable<array<int>> */
    public static function getMoves(): iterable
    {
        yield [0, 0, 5, 5, 5];
        yield [20, 30, 5, 25, 35];
        yield [0, 2, 7, 7, 9];
    }

    #[Test]
    public function testToString(): void
    {
        static::assertSame('(50, 50)', (string) new Point(50, 50));
    }
}
