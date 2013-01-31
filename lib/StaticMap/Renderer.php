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
final class Renderer
{
    const tileSize = 256;

    /**
     * Imagine instance
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;
    
    /**
     * Property containing the image under construction
     * 
     * @var \Imagine\Image\ImageInterface 
     */
    private $resultImage;

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

    public function __construct(\Imagine\Image\ImagineInterface $imagine, $zoom, \StaticMap\Size $size, \StaticMap\LatLng $center, \StaticMap\Tiles $tiles)
    {
        $this->imagine = $imagine;
        $this->zoom = $zoom;
        $this->size = new \Imagine\Image\Box($size->getWidth(), $size->getHeight());
        $this->center = $center;
        $this->tiles = $tiles;
    }
    
    /**
     * Generate the static map image and add blips to it if any are found
     * 
     * @return \Imagine\Image\ImageInterface The resulting image
     */
    public function generate()
    {
        $box = $this->calculateBox();
        
        $this->resultImage = $this->imagine->create(new \Imagine\Image\Box($box['base']->getWidth(), $box['base']->getHeight()));
        $jj = 0;
        for ($i = $box['tiles']['start']->getHeight(); $i < $box['tiles']['stop']->getHeight(); $i++) {
            $ii = 0;
            for ($j = $box['tiles']['start']->getWidth(); $j < $box['tiles']['stop']->getWidth(); $j++) {
                $this->addTile($this->tiles->getTile($j, $i), new \Imagine\Image\Point(($ii * self::tileSize), ($jj * self::tileSize)));
                $ii++;
            }
            $jj++;
        }
        
        $this->resultImage->crop(new \Imagine\Image\Point($box['crop']->getWidth(), $box['crop']->getHeight()), $this->size);

        foreach ($this->blips as $blip) {
            $this->drawBlip($blip);
        }
        
        return $this->resultImage;
    }
    
    /**
     * Add a blip to the center of the image
     * 
     * @param string $image
     */
    public function addCenterBlip($image = null)
    {
        if (is_null($image)) {
            $image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
        }
        $imageSize = getimagesize($image);
        $this->addBlip(
            new \Imagine\Image\Point((($this->size->getWidth() - $imageSize[0]) / 2), (($this->size->getHeight() - $imageSize[1]) / 2)),
            $image
        );
    }
    
    /**
     * Add a blip the collection of blips to be drawn
     * 
     * @param \Imagine\Image\Point $position
     * @param string $image
     */
    public function addBlip(\Imagine\Image\Point $position, $image)
    {
        $this->blips[] = array(
            'position' => $position,
            'image' => $image,
        );
    }
    
    /**
     * Add a tile to the base image
     * 
     * @param string $tileFileName
     * @param \Imagine\Image\Point $point
     * @todo Get rid of the try catch
     */
    protected function addTile($tileFileName, \Imagine\Image\Point $point)
    {
        try {
            $this->resultImage->paste(
                $this->imagine->open($tileFileName),
                $point
            );
        } catch(\Exception $e) {}
    }
    
    /**
     * Draw a blip on the image
     * 
     * @param array $blip
     * @todo Get rid of the try catch
     */
    protected function drawBlip($blip)
    {
        try {
            $this->resultImage->paste(
                $this->imagine->open($blip['image']),
                $blip['position']
            );
        } catch(\Exception $e) {}
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