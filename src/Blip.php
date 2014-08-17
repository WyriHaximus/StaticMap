<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2013 - 2014 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;

use Imagine\Image\Box;

/**
 * Class Blip
 *
 * @package WyriHaximus\StaticMap
 */
class Blip {

    /**
     * @var LatLng
     */
    protected $latLng;

    /**
     * @var
     */
    protected $image;

    /**
     * @var array
     */
    protected $imageSize;

    /**
     * @param LatLng $latLng
     * @param string|null $image
     *
     * @return Blip
     */
    public static function create(LatLng $latLng, $image = null) {
		if (is_null($image) || !file_exists($image)) {
			$image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
		}

        $instance = new self($latLng, $image);
        
        return $instance;
    }

    /**
     * @param LatLng $latLng
     * @param string $image
     */
    public function __construct(LatLng $latLng, $image) {
		$this->latLng = $latLng;
		$this->image = $image;
		$this->imageSize = getimagesize($image);
	}

    /**
     * @return LatLng
     */
    public function getLatLng() {
		return $this->latLng;
	}

    /**
     * @return string
     */
    public function getImage() {
		return $this->image;
	}

    /**
     * @param Point $center
     * @param \Imagine\Image\Box $size
     * @param int $zoom
     *
     * @return Point
     */
    public function calculatePosition(Point $center, Box $size, $zoom) {
		$topLeft = new Point(
			$center->getX() - ($size->getWidth() / 2),
			$center->getY() - ($size->getHeight() / 2)
		);
		$blipPoint = Geo::calculatePoint($this->latLng, $zoom);

		return new Point(
			$blipPoint->getX() - $topLeft->getX() - ($this->imageSize[0] / 2),
			$blipPoint->getY() - $topLeft->getY() - ($this->imageSize[1] / 2)
		);
	}
    
}