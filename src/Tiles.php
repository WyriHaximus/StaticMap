<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2013 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;
/**
 * Renderer using given Imagine instance.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class Tiles
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $fallbackImage;

    /**
     * @param string $location
     * @param string $fallbackImage
     */
    public function __construct($location, $fallbackImage = '')
    {
        $this->location = $location;
        $this->fallbackImage = $fallbackImage;
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return string
     */
    public function getTile($x, $y)
    {
        $fileName = str_replace(array(
            '{x}',
            '{y}',
        ), array(
            $x,
            $y,
        ), $this->location);

        if (empty($this->fallbackImage) || file_exists($fileName)) {
            return $fileName;
        } else {
            return $this->fallbackImage;
        }
    }

}
