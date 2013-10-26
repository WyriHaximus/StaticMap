<?php

namespace StaticMap\Tests;

class GeoTest extends \PHPUnit_Framework_TestCase
{
	public function testCalculateBox()
	{
		$box = \StaticMap\Geo::calculateBox(new \Imagine\Image\Box(25, 25), \StaticMap\Geo::calculatePoint(new \StaticMap\LatLng(71, 111), 3));

		$this->assertTrue($box['tiles']['start'] instanceof \StaticMap\Point);
		$this->assertEquals(6, $box['tiles']['start']->getX());
		$this->assertEquals(1, $box['tiles']['start']->getY());

		$this->assertTrue($box['tiles']['stop'] instanceof \StaticMap\Point);
		$this->assertEquals(9, $box['tiles']['stop']->getX());
		$this->assertEquals(4, $box['tiles']['stop']->getY());

		$this->assertTrue($box['crop'] instanceof \StaticMap\Point);
		$this->assertEquals(363, $box['crop']->getX());
		$this->assertEquals(429, $box['crop']->getY());

		$this->assertTrue($box['base'] instanceof \Imagine\Image\Box);
		$this->assertEquals(768, $box['base']->getWidth());
		$this->assertEquals(768, $box['base']->getHeight());
	}

	public function testCalculatePointProvider() {
		return array(
			// #1
			array(
				new \StaticMap\LatLng(71, 111),
				3,
				new \StaticMap\Point(1655, 441.29647761708),
			),
			// #2
			array(
				new \StaticMap\LatLng(-50, 66),
				1,
				new \StaticMap\Point(349, 338.35787539394),
			),
			// #3
			array(
				new \StaticMap\LatLng(-189, 53),
				7,
				new \StaticMap\Point(21208, 16384),
			),
		);
	}
	/**
	 * @dataProvider testCalculatePointProvider
	 */
	public function testCalculatePoint(\StaticMap\LatLng $latLon, $zoom, \StaticMap\Point $point) {
		$resultPoint = \StaticMap\Geo::calculatePoint($latLon, $zoom);
		$this->assertEquals($point, $resultPoint);
	}

}
