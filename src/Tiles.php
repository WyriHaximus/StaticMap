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

use React\Promise\Deferred;
use WyriHaximus\StaticMap\Loader\LoaderInterface;

/**
 * Renderer using given Imagine instance.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class Tiles
{
    /**
     * Tiles location.
     *
     * @var string
     */
    private $location;

    /**
     * Fallback image in case no tile image can be found.
     *
     * @var string
     */
    private $fallbackImage;

    /**
     * File loader.
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     * Constructor.
     *
     * @param string $location      Tiles location.
     * @param string $fallbackImage Fallback image in case no tile image can be found.
     */
    public function __construct($location, $fallbackImage = '')
    {
        $this->location = $location;
        $this->fallbackImage = $fallbackImage;
    }

    /**
     * Return file name through a promise.
     *
     * @param integer $xAxis X coordinate.
     * @param integer $yAxis Y coordinate.
     *
     * @return \React\Promise\Promise
     */
    public function getTile($xAxis, $yAxis)
    {
        $fileName = str_replace(['{x}', '{y}'], [$xAxis, $yAxis], $this->location);

        $deferred = new Deferred();

        $this->
            loader->
            imageExists($fileName)->
            then(function () use ($deferred, $fileName) {
                $deferred->resolve($fileName);
            }, function () use ($deferred, $fileName) {
                if (empty($this->fallbackImage)) {
                    return $deferred->resolve($fileName);
                }

                $deferred->resolve($this->fallbackImage);
            })
        ;

        return $deferred->promise();
    }

    /**
     * Set the file loader.
     *
     * @param LoaderInterface $loader File loader.
     *
     * @return void
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
}
