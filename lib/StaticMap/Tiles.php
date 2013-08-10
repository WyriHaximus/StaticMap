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

final class Tiles
{
    private $location;
    private $fallbackImage;

    public function __construct($location, $fallbackImage = '')
    {
        $this->location = $location;
        $this->fallbackImage = $fallbackImage;
    }

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
