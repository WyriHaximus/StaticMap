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
use RuntimeException;
use WyriHaximus\StaticMap\Blip\ImageSize;

use function file_exists;
use function getimagesize;

use const DIRECTORY_SEPARATOR;

final class Blip
{
    /**
     * Width height array.
     */
    protected ImageSize $imageSize;

    /**
     * Factory method.
     *
     * @param LatLng      $latLng Coordinate object.
     * @param string|null $image  Image filename or null to fallback to the default.
     */
    public static function create(LatLng $latLng, string|null $image = null): Blip
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
    public function __construct(protected LatLng $latLng, protected string $image)
    {
        $imageSize = getimagesize($this->image);
        if ($imageSize === false) {
            throw new RuntimeException('Unable to get image size');
        }

        $this->imageSize = new ImageSize($imageSize[0], $imageSize[1]);
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
            $center->x - ($size->getWidth() / 2),
            $center->y - ($size->getHeight() / 2),
        );
        $blipPoint = Geo::calculatePoint($this->latLng, $zoom);

        return new Point(
            (int) ($blipPoint->x - $topLeft->x - ($this->imageSize->x / 2)),
            (int) ($blipPoint->y - $topLeft->y - ($this->imageSize->y / 2)),
        );
    }
}
