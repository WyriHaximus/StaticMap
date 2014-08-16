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
class Renderer
{
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
     * @var \Imagine\Image\Box
     */
    private $size;
    
    /**
     * LatLng center
     * @var \WyriHaximus\StaticMap\LatLng
     */
    private $center;

    /**
     * LatLng center Point
     * @var Point
     */
    private $centerPoint;
    
    /**
     * Tile image resolver
     * @var \WyriHaximus\StaticMap\Tiles
     */
    private $tiles;
    
    /**
     * Array with blips (points of interest)
     * @var array
     */
    private $blips = array();
    
    public function __construct(\Imagine\Image\ImagineInterface $imagine, $zoom, \Imagine\Image\Box $size, LatLng $center, Tiles $tiles)
    {
        $this->imagine = $imagine;
        $this->zoom = $zoom;
        $this->size = $size;
        $this->center = $center;
        $this->centerPoint = Geo::calculatePoint($this->center, $this->zoom);
        $this->tiles = $tiles;
    }
    
    /**
     * Generate the static map image and add blips to it if any are found
     * 
     * @return \Imagine\Image\ImageInterface The resulting image
     */
    public function generate()
    {
        $box = Geo::calculateBox($this->size, $this->centerPoint);
        
        $this->resultImage = $this->imagine->create($box['base']);
        $jj = 0;

		$xStart = $box['tiles']['start']->getX();
		$xStop = $box['tiles']['stop']->getX();
		$yStart = $box['tiles']['start']->getY();
		$yStop = $box['tiles']['stop']->getY();

        for ($i = ($yStart - 1); $i < $yStop; $i++) {
            $ii = 0;
            for ($j = ($xStart - 1); $j < $xStop; $j++) {
                $this->addTile($this->tiles->getTile($j, $i), new Point(($ii * Geo::TILE_SIZE), ($jj * Geo::TILE_SIZE)));
                $ii++;
            }
            $jj++;
        }
        
        $this->resultImage->crop($box['crop'], $this->size);
        
        foreach ($this->blips as $blip) {
            $this->drawBlip($blip);
        }
        
        return $this->resultImage;
    }
    
    /**
     * Add a blip to the center of the image
     * 
     * @param string|null $image
     */
    public function addCenterBlip($image = null)
    {
        $this->addBlip(Blip::create($this->center, $image));
    }
    
    /**
     * Add a blip the collection of blips to be drawn
     * 
     * @param Blip $blip
     */
    public function addBlip(Blip $blip)
    {
        $this->blips[] = $blip;
    }
    
    /**
     * Add a tile to the base image
     * 
     * @param string $tileFileName
     * @param Point $point
     */
    protected function addTile($tileFileName, Point $point)
    {
        try {
            $this->resultImage->paste(
                $this->imagine->open($tileFileName),
                $point
            );
        } catch(\Exception $e) {
            // Most likely an exception about a out of bounds past, we'll just ignore that
        }
    }
    
    /**
     * Draw a blip on the image
     * 
     * @param \WyriHaximus\StaticMap\Blip $blip
     */
    protected function drawBlip(Blip $blip)
    {
        try {
            $this->resultImage->paste(
                $this->imagine->open($blip->getImage()),
				$blip->calculatePosition($this->centerPoint, $this->size, $this->zoom)
            );
        } catch(\Exception $e) {
            // Most likely an exception about a out of bounds past, we'll just ignore that
        }
    }
    
}