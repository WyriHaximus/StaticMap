<?php

declare(strict_types=1);

/*
 * This file is part of StaticMap.
 *
 * (c) 2012 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;

use function floatval;

/**
 * Storage for lat, lng pair values.
 */
final class LatLng
{
    /**
     * Value for the lat
     */
    private int $lat;

    /**
     * Value for the lng
     */
    private int $lng;

    /**
     * Store Size value's.
     *
     * All value's will be sanitized and forced in a certain range.
     *
     * @param int $lat lat
     * @param int $lng lng
     */
    public function __construct(int $lat, int $lng)
    {
        $this->setLat($lat);
        $this->setLng($lng);
    }

    /**
     * Set the value for the lat and returns the stored value
     */
    public function setLat(int $lat): int
    {
        $this->lat = $this->sanitized($lat, -90, 90, $this->lat);

        return $this->lat;
    }

    /**
     * Returns the value for the lat
     */
    public function getLat(): int
    {
        return $this->lat;
    }

    /**
     * Set the value for the lng and returns the stored value
     */
    public function setLng(int $lng): int
    {
        $this->lng = $this->sanitized($lng, -180, 180, $this->lng);

        return $this->lng;
    }

    /**
     * Returns the value for the lng
     */
    public function getLng(): int
    {
        return $this->lng;
    }

    /**
     * Sanitize value to be int and between $rangeBegin and $rangeend.
     *
     * @param  int $value      Value to be sanitized
     * @param  int $rangeBegin Begin of the range
     * @param  int $rangeEnd   End of the range
     * @param  int $default    Value to use incase $value is invalid
     */
    private function sanitized(int $value, int $rangeBegin, int $rangeEnd, int $default): int
    {
        $value = floatval($value);

        if ($value >= $rangeBegin && $rangeEnd >= $value) {
            return $value;
        }

        return $default;
    }
}
