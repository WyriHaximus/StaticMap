<?php

namespace StaticMap\Tests;

class BlipTest extends \PHPUnit_Framework_TestCase {

	public function testCreateProvider() {
		$defaultImage = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'StaticMap' . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
		return array(
			array(
				new \StaticMap\LatLng(71, 111),
				new \StaticMap\Blip(new \StaticMap\LatLng(71, 111), $defaultImage),
			),
			array(
				new \StaticMap\LatLng(-50, 66),
				new \StaticMap\Blip(new \StaticMap\LatLng(-50, 66), $defaultImage),
			),
			array(
				new \StaticMap\LatLng(-189, 53),
				new \StaticMap\Blip(new \StaticMap\LatLng(-189, 53), $defaultImage),
			),
		);
	}
	/**
	 * @dataProvider testCreateProvider
	 */
	public function testCreate(\StaticMap\LatLng $latLng, $result) {
		$resultBlip = \StaticMap\Blip::create($latLng);
		$this->assertEquals($result, $resultBlip);
	}

	public function testGetImageProvider() {
		$defaultImage = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'StaticMap' . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';

		return array(
			array(
				new \StaticMap\LatLng(71, 111),
				$defaultImage,
				$defaultImage,
			),
			array(
				new \StaticMap\LatLng(-50, 66),
				__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg',
				__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg',
			),
			array(
				new \StaticMap\LatLng(-189, 53),
				__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'Simple' . DIRECTORY_SEPARATOR . '1' . DIRECTORY_SEPARATOR . '2.png',
				$defaultImage,
			),
			array(
				new \StaticMap\LatLng(-189, 53),
				null,
				$defaultImage,
			),
		);
	}
	/**
	 * @dataProvider testGetImageProvider
	 */
	public function testGetImage(\StaticMap\LatLng $latLng, $image, $result) {
		$resultBlip = \StaticMap\Blip::create($latLng, $image);
		$this->assertEquals($result, $resultBlip->getImage());
	}

	public function testGetLatLngProvider() {
		return array(
			array(
				new \StaticMap\LatLng(71, 111),
			),
			array(
				new \StaticMap\LatLng(-50, 66),
			),
			array(
				new \StaticMap\LatLng(-189, 53),
			),
		);
	}
	/**
	 * @dataProvider testGetLatLngProvider
	 */
	public function testGetLatLng(\StaticMap\LatLng $latLng) {
		$resultBlip = \StaticMap\Blip::create($latLng);
		$this->assertEquals($latLng, $resultBlip->getLatLng());
	}

	public function testCalculatePositionProvider() {
		return array(
			array(
				new \StaticMap\Point(1097, 949.40161077744),
				3,
				new \StaticMap\LatLng(13, 13),
				new \Imagine\Image\Box(12, 12),
				new \StaticMap\Point(0.5, 0.5),
			),
		);
	}
	/**
	 * @dataProvider testCalculatePositionProvider
	 */
	public function testCalculatePosition(\StaticMap\Point $center, $zoom, \StaticMap\LatLng $latLngBlip, \Imagine\Image\Box $size, \StaticMap\Point $blipPoint) {
		$resultBlip = \StaticMap\Blip::create($latLngBlip);
		$this->assertEquals($blipPoint, $resultBlip->calculatePosition($center, $size, $zoom));
	}

}