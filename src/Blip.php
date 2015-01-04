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
class Blip
{
    /**
     * Latitude Longitude coordinates for this Blip.
     *
     * @var LatLng
     */
    protected $latLng;

    /**
     * Image filename.
     *
     * @var string
     */
    protected $image;

    /**
     * Width height array.
     *
     * @var array
     */
    protected $imageSize;

    /**
     * Factory method.
     *
     * @param LatLng      $latLng Coordinate object.
     * @param string|null $image  Image filename or null to fallback to the default.
     *
     * @return Blip
     */
    public static function create(LatLng $latLng, $image = null)
    {
        if (is_null($image) || !file_exists($image)) {
            $image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
        }

        return new self($latLng, $image);
    }

    /**
     * Constructor.
     *
     * @param LatLng $latLng Coordinate object.
     * @param string $image  Image filename.
     */
    public function __construct(LatLng $latLng, $image)
    {
        $this->latLng = $latLng;
        $this->image = $image;
        $this->imageSize = getimagesize($image);
    }

    /**
     * Return the coordinate object.
     *
     * @return LatLng
     */
    public function getLatLng()
    {
        return $this->latLng;
    }

    /**
     * Return the image filename.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Calculate the position of the blip on the map image.
     *
     * @param Point   $center Point on the image.
     * @param Box     $size   Size of the image.
     * @param integer $zoom   Zoom level of the map.
     *
     * @return Point
     */
    public function calculatePosition(Point $center, Box $size, $zoom)
    {
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
