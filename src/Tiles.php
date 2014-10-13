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
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $fallbackImage;

    /**
     * @var LoaderInterface
     */
    private $loader;

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
     * @param integer $x
     * @param integer $y
     *
     * @return \React\Promise\Promise
     */
    public function getTile($x, $y)
    {
        $fileName = str_replace(
            [
                '{x}',
                '{y}',
            ],
            [
                $x,
                $y,
            ],
            $this->location
        );

        $deferred = new Deferred();

        $this->loader->imageExists($fileName)->then(
            function () use ($deferred, $fileName) {
                $deferred->resolve($fileName);
            },
            function () use ($deferred, $fileName) {
                if (empty($this->fallbackImage)) {
                    $deferred->resolve($fileName);
                } else {
                    $deferred->resolve($this->fallbackImage);
                }
            }
        );

        return $deferred->promise();
    }

    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
}
