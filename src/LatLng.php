<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2012 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;

/**
 * Storage for lat, lng pair values.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class LatLng
{
    /**
     * Value for the lat
     * @var double
     */
    private $lat;

    /**
     * Value for the lng
     * @var double
     */
    private $lng;

    /**
     * Store Size value's.
     *
     * All value's will be sanitized and forced in a certain range.
     *
     * @param double $lat lat
     * @param double $lng lng
     */
    public function __construct($lat, $lng)
    {
        $this->setLat($lat);
        $this->setLng($lng);
    }

    /**
     * Set the value for the lat and returns the stored value
     * @param  double $lat
     * @return double
     */
    public function setLat($lat)
    {
        $this->lat = $this->sanitized($lat, -90, 90, $this->lat);

        return $this->lat;
    }

    /**
     * Returns the value for the lat
     * @return double
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set the value for the lng and returns the stored value
     * @param  double $lng
     * @return double
     */
    public function setLng($lng)
    {
        $this->lng = $this->sanitized($lng, -180, 180, $this->lng);

        return $this->lng;
    }

    /**
     * Returns the value for the lng
     * @return double
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Sanitize value to be int and between $rangeBegin and $rangeend.
     *
     * @param  double $value      Value to be sanitized
     * @param  double $rangeBegin Begin of the range
     * @param  double $rangeEnd   End of the range
     * @param  double $default    Value to use incase $value is invalid
     * @return double
     */
    private function sanitized($value, $rangeBegin, $rangeEnd, $default)
    {
        $value = floatval($value);

        if ($value >= $rangeBegin && $rangeEnd >= $value) {
            return $value;
        } else {
            return $default;
        }
    }

}
