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
 * Abstract renderer providing a based for Gd and Convert renderers.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
abstract class Renderer
{
    const tileSize = 256;

    /**
     * Value for the zoom
     * @var int
     */
    private $zoom;

    /**
     * Image size
     * @var \StaticMap\Size
     */
    private $size;

    /**
     * LatLng center
     * @var \StaticMap\LatLng
     */
    private $center;

    /**
     * Tile image resolver
     * @var \StaticMap\Tiles
     */
    private $tiles;

    /**
     * Array with blips (points of interest)
     * @var array
     */
    private $blips = array();

    public function __construct($zoom, \StaticMap\Size $size, \StaticMap\LatLng $center, \StaticMap\Tiles $tiles)
    {
        $this->zoom = $zoom;
        $this->size = $size;
        $this->center = $center;
        $this->tiles = $tiles;
    }

    public function generate()
    {
        $box = $this->calculateBox();

        $this->createCropImage($box['base']);

        $jj = 0;
        for ($i = $box['tiles']['start']->getHeight(); $i <= $box['tiles']['stop']->getHeight(); $i++) {
            $ii = 0;
            for ($j = $box['tiles']['start']->getWidth(); $j <= $box['tiles']['stop']->getWidth(); $j++) {
                $this->addTile($this->tiles->getTile($j, $i), new \StaticMap\Size(($ii * self::tileSize), ($jj * self::tileSize)));
                $ii++;
            }
            $jj++;
        }

        $this->createBaseImage($this->size);
        $this->crop($box['crop'], $this->size);

        foreach ($this->blips as $blip) {
            $this->drawBlip($blip);
        }
    }

    public function addCenterBlip()
    {
        $imageSize = getimagesize(__DIR__ . 'Img' . DIRECTORY_SEPARATOR . 'blip.png');
        $this->addBlip(new \StaticMap\Size((($this->size->getWidth() - $imageSize[0]) / 2), (($this->size->getHeight() - $imageSize[1]) / 2)), __DIR__ . 'Img' . DIRECTORY_SEPARATOR . 'blip.png');
    }

    public function addBlip($position, $image)
    {
        $this->blips[] = array(
            'position' => $position,
            'image' => $image,
            'imageSize' => getimagesize($image),
        );
    }

    /**
     *
     * @return array
     * @todo The math in this function might be done in a 'better' way
     */
    protected function calculateBox()
    {
        $max_height_count = ceil($this->size->getHeight() / self::tileSize);
        $max_width_count = ceil($this->size->getWidth() / self::tileSize);

        $tile_count = pow(2, $this->zoom);
        $pixel_count = $tile_count * self::tileSize;

        $x = ($pixel_count * (180 + $this->center->getLng()) / 360) % $pixel_count;

        $lat = ($this->center->getLat() * M_PI) / 180;

        $y = log(tan(($lat / 2) + M_PI_4));
        $y = ($pixel_count / 2) - ($pixel_count * $y / (2 * M_PI));

        $tile_height_start = floor($y / self::tileSize) - floor($max_height_count / 2);
        $tile_width_start = floor($x / self::tileSize) - floor($max_width_count / 2);

        $tile_height_stop = $tile_height_start + $max_height_count + 2;
        $tile_width_stop = $tile_width_start + $max_width_count + 2;

        $upper_y = $y - floor($this->size->getHeight() / 2) - ($tile_height_start * self::tileSize);
        $upper_x = $x - floor($this->size->getWidth() / 2) - ($tile_width_start * self::tileSize);

        return array(
            'tiles' => array(
                'start' => new \StaticMap\Size($tile_width_start - 1, $tile_height_start - 1),
                'stop' => new \StaticMap\Size($tile_width_stop, $tile_height_stop),
            ),
            'crop' => new \StaticMap\Size($upper_x + self::tileSize, $upper_y + self::tileSize),
            'base' => new \StaticMap\Size((($max_width_count + 2) * self::tileSize), (($max_height_count + 2) * self::tileSize)),
        );
    }

}
