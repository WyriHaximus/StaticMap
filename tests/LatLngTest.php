<?php

namespace WyriHaximus\StaticMap\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus\StaticMap\LatLng;

class LatLngTest extends TestCase
{
    public function testConstructor()
    {
        $LatLng = new LatLng(35, 45);

        $this->assertEquals(35, $LatLng->getLat());
        $this->assertEquals(45, $LatLng->getLng());
    }

    public function testSetters()
    {
        $LatLng = new LatLng(35, 45);

        $LatLng->setLat(55);
        $LatLng->setLng(65);

        $this->assertEquals(55, $LatLng->getLat());
        $this->assertEquals(65, $LatLng->getLng());
    }

    public function testInRange()
    {
        $LatLng = new LatLng(35, 45);

        for ($i = -90; $i <= 90; $i++) {
            $LatLng->setLat($i);
            $this->assertEquals($i, $LatLng->getLat());
        }

        for ($i = -180; $i <= 180; $i++) {
            $LatLng->setLng($i);
            $this->assertEquals($i, $LatLng->getLng());
        }
    }

    public function testOutRange()
    {
        $LatLng = new LatLng(35, 45);

        $LatLng->setLat(-90.1);
        $this->assertEquals(35, $LatLng->getLat());
        $LatLng->setLat(-91);
        $this->assertEquals(35, $LatLng->getLat());

        $LatLng->setLat(90.1);
        $this->assertEquals(35, $LatLng->getLat());
        $LatLng->setLat(91);
        $this->assertEquals(35, $LatLng->getLat());

        $LatLng->setLat(-180.1);
        $this->assertEquals(35, $LatLng->getLat());
        $LatLng->setLat(-181);
        $this->assertEquals(35, $LatLng->getLat());

        $LatLng->setLat(190.1);
        $this->assertEquals(35, $LatLng->getLat());
        $LatLng->setLat(181);
        $this->assertEquals(35, $LatLng->getLat());
    }

    public function testNonInt()
    {
        $LatLng = new LatLng('a', 'b');

        $this->assertEquals(0, $LatLng->getLat());
        $this->assertEquals(0, $LatLng->getLng());
    }

    public function testNonIntGetter()
    {
        $LatLng = new LatLng('a', 'b');

        $LatLng->setLat('c');
        $LatLng->setLng('d');

        $this->assertEquals(0, $LatLng->getLat());
        $this->assertEquals(0, $LatLng->getLng());
    }
}
