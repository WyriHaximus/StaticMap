<?php

namespace WyriHaximus\StaticMap\Tests;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use WyriHaximus\StaticMap\Point;

class PointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers       WyriHaximus\StaticMap\Point::getX
     * @covers       WyriHaximus\StaticMap\Point::getY
     * @covers       WyriHaximus\StaticMap\Point::in
     *
     * @dataProvider getCoordinates
     *
     * @param integer $x
     * @param integer $y
     * @param BoxInterface $box
     * @param Boolean $expected
     */
    public function testShouldAssignXYCoordinates($x, $y, BoxInterface $box, $expected)
    {
        $coordinate = new Point($x, $y);

        $this->assertEquals($x, $coordinate->getX());
        $this->assertEquals($y, $coordinate->getY());

        $this->assertEquals($expected, $coordinate->in($box));
    }

    /**
     * Data provider for testShouldAssignXYCoordinates
     *
     * @return array
     */
    public function getCoordinates()
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
     *
     * @dataProvider getMoves
     *
     * @param integer $x
     * @param integer $y
     * @param integer $move
     * @param integer $x1
     * @param integer $y1
     */
    public function testShouldMoveByGivenAmount($x, $y, $move, $x1, $y1)
    {
        $point = new Point($x, $y);
        $shift = $point->move($move);

        $this->assertInstanceOf('\WyriHaximus\StaticMap\Point', $shift);
        $this->assertEquals($x1, $shift->getX());
        $this->assertEquals($y1, $shift->getY());
    }

    public function getMoves()
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
    public function testToString()
    {
        $this->assertEquals('(50, 50)', (string)new Point(50, 50));
    }
}
