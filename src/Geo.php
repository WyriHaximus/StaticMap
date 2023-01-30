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
use Imagine\Image\PointInterface;

use function ceil;
use function floor;
use function log;
use function pow;
use function round;
use function tan;

use const M_PI;
use const M_PI_4;

/**
 * Geo calculations.
 */
final class Geo
{
    public const TILE_SIZE = 256;

    /**
     * Calculate the box variables by the given size and center
     *
     * @return array<mixed>
     */
    public static function calculateBox(Box $size, PointInterface $center): array
    {
        $maxHeightCount = ceil($size->getHeight() / self::TILE_SIZE);
        $maxWidthCount  = ceil($size->getWidth() / self::TILE_SIZE);

        $tileHeightStart = floor($center->getY() / self::TILE_SIZE) - floor($maxHeightCount / 2);
        $tileWidthStart  = floor($center->getX() / self::TILE_SIZE) - floor($maxWidthCount / 2);

        $tileHeightStop = $tileHeightStart + $maxHeightCount + 2;
        $tileWidthStop  = $tileWidthStart + $maxWidthCount + 2;

        $upperY = $center->getY() - floor($size->getHeight() / 2) - ($tileHeightStart * self::TILE_SIZE);
        $upperX = $center->getX() - floor($size->getWidth() / 2) - ($tileWidthStart * self::TILE_SIZE);

        return [
            'tiles' => [
                'start' => new Point($tileWidthStart, $tileHeightStart),
                'stop' => new Point($tileWidthStop, $tileHeightStop),
            ],
            'crop' => new Point(round($upperX + self::TILE_SIZE), round($upperY + self::TILE_SIZE)),
            'base' => new Box(
                ($maxWidthCount + 2) * self::TILE_SIZE,
                ($maxHeightCount + 2) * self::TILE_SIZE
            ),
        ];
    }

    /**
     * Calculate the pixel point for the given latitude and longitude
     */
    public static function calculatePoint(LatLng $latLon, int $zoom): PointInterface
    {
        $tileCount  = pow(2, $zoom);
        $pixelCount = $tileCount * self::TILE_SIZE;

        $x   = ($pixelCount * (180 + $latLon->getLng()) / 360) % $pixelCount;
        $lat = $latLon->getLat() * M_PI / 180;
        $y   = log(tan(($lat / 2) + M_PI_4));
        $y   = ($pixelCount / 2) - ($pixelCount * $y / (2 * M_PI));

        return new Point($x, $y);
    }
}
