<?php

declare(strict_types=1);

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
use JetBrains\PhpStorm\ArrayShape;

use function file_exists;
use function getimagesize;

use const DIRECTORY_SEPARATOR;

final class Blip
{
    /**
     * Latitude Longitude coordinates for this Blip.
     */
    protected LatLng $latLng;

    /**
     * Image filename.
     */
    protected string $image;

    /**
     * Width height array.
     *
     * @var array<int>
     */
    #[ArrayShape([0 => 'int', 1 => 'int', 2 => 'int', 3 => 'string', 'bits' => 'int', 'channels' => 'int', 'mime' => 'string'])]
    protected array $imageSize;

    /**
     * Factory method.
     *
     * @param LatLng      $latLng Coordinate object.
     * @param string|null $image  Image filename or null to fallback to the default.
     */
    public static function create(LatLng $latLng, ?string $image = null): Blip
    {
        if ($image === null || ! file_exists($image)) {
            $image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
        }

        return new self($latLng, $image);
    }

    /**
     * @param LatLng $latLng Coordinate object.
     * @param string $image  Image filename.
     */
    public function __construct(LatLng $latLng, string $image)
    {
        $this->latLng    = $latLng;
        $this->image     = $image;
        $this->imageSize = getimagesize($image);
    }

    /**
     * Return the coordinate object.
     */
    public function getLatLng(): LatLng
    {
        return $this->latLng;
    }

    /**
     * Return the image filename.
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Calculate the position of the blip on the map image.
     *
     * @param Point $center Point on the image.
     * @param Box   $size   Size of the image.
     * @param int   $zoom   Zoom level of the map.
     */
    public function calculatePosition(Point $center, Box $size, int $zoom): Point
    {
        $topLeft   = new Point(
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
