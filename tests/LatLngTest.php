<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests;

use WyriHaximus\StaticMap\LatLng;
use WyriHaximus\TestUtilities\TestCase;

final class LatLngTest extends TestCase
{
    public function testConstructor(): void
    {
        $latLng = new LatLng(35, 45);

        $this->assertEquals(35, $latLng->getLat());
        $this->assertEquals(45, $latLng->getLng());
    }

    public function testSetters(): void
    {
        $latLng = new LatLng(35, 45);

        $latLng->setLat(55);
        $latLng->setLng(65);

        $this->assertEquals(55, $latLng->getLat());
        $this->assertEquals(65, $latLng->getLng());
    }

    public function testInRange(): void
    {
        $latLng = new LatLng(35, 45);

        for ($i = -90; $i <= 90; $i++) {
            $latLng->setLat($i);
            $this->assertEquals($i, $latLng->getLat());
        }

        for ($i = -180; $i <= 180; $i++) {
            $latLng->setLng($i);
            $this->assertEquals($i, $latLng->getLng());
        }
    }

    public function testOutRange(): void
    {
        $latLng = new LatLng(35, 45);

        $latLng->setLat(-90.1);
        $this->assertEquals(35, $latLng->getLat());
        $latLng->setLat(-91);
        $this->assertEquals(35, $latLng->getLat());

        $latLng->setLat(90.1);
        $this->assertEquals(35, $latLng->getLat());
        $latLng->setLat(91);
        $this->assertEquals(35, $latLng->getLat());

        $latLng->setLat(-180.1);
        $this->assertEquals(35, $latLng->getLat());
        $latLng->setLat(-181);
        $this->assertEquals(35, $latLng->getLat());

        $latLng->setLat(190.1);
        $this->assertEquals(35, $latLng->getLat());
        $latLng->setLat(181);
        $this->assertEquals(35, $latLng->getLat());
    }

    public function testNonInt(): void
    {
        $latLng = new LatLng('a', 'b');

        $this->assertEquals(0, $latLng->getLat());
        $this->assertEquals(0, $latLng->getLng());
    }

    public function testNonIntGetter(): void
    {
        $latLng = new LatLng('a', 'b');

        $latLng->setLat('c');
        $latLng->setLng('d');

        $this->assertEquals(0, $latLng->getLat());
        $this->assertEquals(0, $latLng->getLng());
    }
}
