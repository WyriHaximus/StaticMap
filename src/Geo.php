<?php

declare(strict_types=1);

/*
 * This file is part of StaticMap.
 *
 * (c) 2013 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;

use Imagine\Image\Box;

use function assert;
use function ceil;
use function floor;
use function is_int;
use function log;
use function round;
use function tan;

use const M_PI;
use const M_PI_4;

/**
 * Geo calculations.
 */
final class Geo
{
    public const int TILE_SIZE = 256;

    /**
     * Calculate the box variables by the given size and center
     */
    public static function calculateBox(Box $size, Point $center): Geo\Box
    {
        $maxHeightCount = ceil($size->getHeight() / self::TILE_SIZE);
        $maxWidthCount  = ceil($size->getWidth() / self::TILE_SIZE);

        $tileHeightStart = floor($center->y / self::TILE_SIZE) - floor($maxHeightCount / 2);
        $tileWidthStart  = floor($center->x / self::TILE_SIZE) - floor($maxWidthCount / 2);

        $tileHeightStop = $tileHeightStart + $maxHeightCount + 2;
        $tileWidthStop  = $tileWidthStart + $maxWidthCount + 2;

        $upperY = $center->y - floor($size->getHeight() / 2) - ($tileHeightStart * self::TILE_SIZE);
        $upperX = $center->x - floor($size->getWidth() / 2) - ($tileWidthStart * self::TILE_SIZE);

        return new Geo\Box(
            new Geo\Box\Tiles(
                new Point((int) $tileWidthStart, (int) $tileHeightStart),
                new Point((int) $tileWidthStop, (int) $tileHeightStop),
            ),
            new Point((int) round($upperX + self::TILE_SIZE), (int) round($upperY + self::TILE_SIZE)),
            new Box(
                (int) (($maxWidthCount + 2) * self::TILE_SIZE),
                (int) (($maxHeightCount + 2) * self::TILE_SIZE),
            ),
        );
    }

    /**
     * Calculate the pixel point for the given latitude and longitude
     */
    public static function calculatePoint(LatLng $latLon, int $zoom): Point
    {
        $tileCount = 2 ** $zoom;
        assert(is_int($tileCount));
        $pixelCount = $tileCount * self::TILE_SIZE;

        $x   = ((int) round($pixelCount * (180 + $latLon->lng) / 360)) % $pixelCount;
        $lat = $latLon->lat * M_PI / 180;
        $y   = log(tan(($lat / 2) + M_PI_4));
        $y   = ($pixelCount / 2) - ($pixelCount * $y / (2 * M_PI));

        return new Point($x, (int) $y);
    }
}
