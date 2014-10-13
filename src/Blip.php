<?php

namespace WyriHaximus\StaticMap;

use Imagine\Image\Box;

class Blip
{

    protected $latLng;
    protected $image;
    protected $imageSize;

    public static function create(LatLng $latLng, $image = null)
    {
        if (is_null($image) || !file_exists($image)) {
            $image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
        }

        $instance = new self($latLng, $image);

        return $instance;
    }

    public function __construct(LatLng $latLng, $image)
    {
        $this->latLng = $latLng;
        $this->image = $image;
        $this->imageSize = getimagesize($image);
    }

    public function getLatLng()
    {
        return $this->latLng;
    }

    public function getImage()
    {
        return $this->image;
    }

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
