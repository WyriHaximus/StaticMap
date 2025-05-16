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
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use React\Promise\PromiseInterface;
use WyriHaximus\StaticMap\Loader\LoaderInterface;
use WyriHaximus\StaticMap\Loader\Simple;

/**
 * Renderer using given Imagine instance.
 */
final class Renderer
{
    /**
     * Property containing the image under construction
     */
    private ImageInterface $resultImage;

    /**
     * LatLng center Point
     */
    private readonly Point $centerPoint;

    /**
     * Array with blips (points of interest)
     *
     * @var array<Blip>
     */
    private array $blips = [];

    private readonly LoaderInterface $loader;

    public function __construct(
        private readonly ImagineInterface $imagine,
        private readonly int $zoom,
        private readonly Box $size,
        private readonly LatLng $center,
        private readonly Tiles $tiles,
        LoaderInterface|null $loader = null,
    ) {
        $this->centerPoint = Geo::calculatePoint($this->center, $this->zoom);

        if (! $loader instanceof LoaderInterface) {
            $loader = new Simple();
        }

        $this->loader = $loader;

        $this->tiles->setLoader($this->loader);
    }

    /**
     * Generate the static map image and add blips to it if any are found
     *
     * @return ImageInterface The resulting image
     */
    public function generate(): ImageInterface
    {
        $box = Geo::calculateBox($this->size, $this->centerPoint);

        $this->resultImage = $this->imagine->create($box->base);
        $jj                = 0;

        $xStart = $box->tiles->start->x;
        $xStop  = $box->tiles->stop->x;
        $yStart = $box->tiles->start->y;
        $yStop  = $box->tiles->stop->y;

        for ($i = $yStart - 1; $i < $yStop; $i++) {
            $ii = 0;
            for ($j = $xStart - 1; $j < $xStop; $j++) {
                $this->addTile(
                    $this->tiles->getTile($j, $i),
                    new Point($ii * Geo::TILE_SIZE, $jj * Geo::TILE_SIZE),
                );
                $ii++;
            }

            $jj++;
        }

        $this->loader->run();

        $this->resultImage->crop($box->crop, $this->size);

        foreach ($this->blips as $blip) {
            $this->drawBlip($blip);
        }

        $this->loader->run();

        return $this->resultImage;
    }

    /**
     * Add a blip to the center of the image.
     *
     * @param string|null $image Image to use as blip.
     */
    public function addCenterBlip(string|null $image = null): void
    {
        $this->addBlip(Blip::create($this->center, $image));
    }

    /**
     * Add a blip the collection of blips to be drawn.
     *
     * @param Blip $blip Added a Blip to the list of blips for on the map.
     */
    public function addBlip(Blip $blip): void
    {
        $this->blips[] = $blip;
    }

    /**
     * Add a tile to the base image.
     *
     * @param PromiseInterface<string> $promise Promise from the get tile.
     * @param Point                    $point   Point where to place the tile.
     */
    private function addTile(PromiseInterface $promise, Point $point): void
    {
        $promise->then(
            fn (string $fileName): PromiseInterface => $this->loader->addImage($fileName),
        )->then(
            function (string $image) use ($point): void {
                $this->drawImage($image, $point);
            },
        );
    }

    /**
     * Draw a blip on the image.
     *
     * @param Blip $blip Blip to draw on the map.
     */
    private function drawBlip(Blip $blip): void
    {
        $this->loader->addImage($blip->getImage())->then(
            function (string $image) use ($blip): void {
                $this->drawImage($image, $blip->calculatePosition($this->centerPoint, $this->size, $this->zoom));
            },
        );
    }

    /**
     * Draw $image on $this->resultImage.
     *
     * @param string $image Image blob.
     * @param Point  $point The point where to draw $image.
     */
    private function drawImage(string $image, Point $point): void
    {
//        try {
            $this->resultImage->paste(
                $this->imagine->load($image),
                $point,
            );
//        } catch (Throwable) {
//            // Most likely an exception about a out of bounds past, we'll just ignore that
//        }
    }
}
