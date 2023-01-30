<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use WyriHaximus\StaticMap\Point;
use WyriHaximus\TestUtilities\TestCase;

final class PointTest extends TestCase
{
    /**
     * @covers       WyriHaximus\StaticMap\Point::getX
     * @covers       WyriHaximus\StaticMap\Point::getY
     * @covers       WyriHaximus\StaticMap\Point::in
     * @dataProvider getCoordinates
     */
    public function testShouldAssignXYCoordinates(int $x, int $y, BoxInterface $box, bool $expected): void
    {
        $coordinate = new Point($x, $y);

        $this->assertEquals($x, $coordinate->getX());
        $this->assertEquals($y, $coordinate->getY());

        $this->assertEquals($expected, $coordinate->in($box));
    }

    /**
     * Data provider for testShouldAssignXYCoordinates
     *
     * @return array<array<int, Box, bool>>
     */
    public function getCoordinates(): array
    {
        return [
            [0, 0, new Box(5, 5), true],
            [5, 15, new Box(5, 5), false],
            [10, 23, new Box(10, 10), false],
            [42, 30, new Box(50, 50), true],
            [81, 16, new Box(50, 10), false],
        ];
    }

    /**
     * @covers       WyriHaximus\StaticMap\Point::getX
     * @covers       WyriHaximus\StaticMap\Point::getY
     * @covers       WyriHaximus\StaticMap\Point::move
     * @dataProvider getMoves
     */
    public function testShouldMoveByGivenAmount(int $x, int $y, int $move, int $x1, int $y1): void
    {
        $point = new Point($x, $y);
        $shift = $point->move($move);

        $this->assertInstanceOf('\WyriHaximus\StaticMap\Point', $shift);
        $this->assertEquals($x1, $shift->getX());
        $this->assertEquals($y1, $shift->getY());
    }

    /**
     * @return array<array<int>>
     */
    public function getMoves(): array
    {
        return [
            [0, 0, 5, 5, 5],
            [20, 30, 5, 25, 35],
            [0, 2, 7, 7, 9],
        ];
    }

    /**
     * @covers WyriHaximus\StaticMap\Point::__toString
     */
    public function testToString(): void
    {
        $this->assertEquals('(50, 50)', (string) new Point(50, 50));
    }
}
