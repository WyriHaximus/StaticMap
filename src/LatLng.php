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

final readonly class LatLng
{
    public float $lat;

    public float $lng;

    public function __construct(float $lat, float $lng)
    {
        $this->lat = $this->sanitized($lat, -90, 90);
        $this->lng = $this->sanitized($lng, -180, 180);
    }

    /**
     * Sanitize value to be int and between $rangeBegin and $rangeEnd.
     *
     * @param  float $value      Value to be sanitized
     * @param  float $rangeBegin Begin of the range
     * @param  float $rangeEnd   End of the range
     */
    private function sanitized(float $value, float $rangeBegin, float $rangeEnd): float
    {
        if ($value >= $rangeBegin && $rangeEnd >= $value) {
            return $value;
        }

        if ($value < $rangeBegin) {
            return $rangeBegin;
        }

        return $rangeEnd;
    }
}
