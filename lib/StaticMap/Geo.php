<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2013 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StaticMap;

/**
 * Geo calculations.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
class Geo
{
	const tileSize = 256;

	/**
	 *
	 * @return array
	 */
	public static function calculateBox($size, $center)
	{
		$max_height_count = ceil($size->getHeight() / self::tileSize);
		$max_width_count = ceil($size->getWidth() / self::tileSize);

		$tile_height_start = floor($center->getY() / self::tileSize) - floor($max_height_count / 2);
		$tile_width_start = floor($center->getX() / self::tileSize) - floor($max_width_count / 2);

		$tile_height_stop = $tile_height_start + $max_height_count + 2;
		$tile_width_stop = $tile_width_start + $max_width_count + 2;

		$upper_y = $center->getY() - floor($size->getHeight() / 2) - ($tile_height_start * self::tileSize);
		$upper_x = $center->getX() - floor($size->getWidth() / 2) - ($tile_width_start * self::tileSize);

		return array(
			'tiles' => array(
				'start' => new Point($tile_width_start, $tile_height_start),
				'stop' => new Point($tile_width_stop, $tile_height_stop),
			),
			'crop' => new Point(round($upper_x + self::tileSize), round($upper_y + self::tileSize)),
			'base' => new \Imagine\Image\Box((($max_width_count + 2) * self::tileSize), (($max_height_count + 2) * self::tileSize)),
		);
	}

	public static function calculatePoint(LatLng $latLon, $zoom) {
		$tile_count = pow(2, $zoom);
		$pixel_count = $tile_count * self::tileSize;

		$x = ($pixel_count * (180 + $latLon->getLng()) / 360) % $pixel_count;
		$lat = ($latLon->getLat() * M_PI) / 180;
		$y = log(tan(($lat / 2) + M_PI_4));
		$y = ($pixel_count / 2) - ($pixel_count * $y / (2 * M_PI));
		return new Point($x, $y);
	}

}