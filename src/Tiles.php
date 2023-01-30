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

use React\Promise\Deferred;
use React\Promise\Promise;
use WyriHaximus\StaticMap\Loader\LoaderInterface;

use function str_replace;

final class Tiles
{
    /**
     * Tiles location.
     */
    private string $location;

    /**
     * Fallback image in case no tile image can be found.
     */
    private string $fallbackImage;

    /**
     * File loader.
     */
    private LoaderInterface $loader;

    /**
     * @param string $location      Tiles location.
     * @param string $fallbackImage Fallback image in case no tile image can be found.
     */
    public function __construct(string $location, string $fallbackImage = '')
    {
        $this->location      = $location;
        $this->fallbackImage = $fallbackImage;
    }

    /**
     * Return file name through a promise.
     *
     * @param int $xAxis X coordinate.
     * @param int $yAxis Y coordinate.
     */
    public function getTile(int $xAxis, int $yAxis): Promise
    {
        $fileName = str_replace(['{x}', '{y}'], [$xAxis, $yAxis], $this->location);

        $deferred = new Deferred();

        $this->
            loader->
            imageExists($fileName)->
            then(static function () use ($deferred, $fileName): void {
                $deferred->resolve($fileName);
            }, function () use ($deferred, $fileName) {
                if (empty($this->fallbackImage)) {
                    return $deferred->resolve($fileName);
                }

                $deferred->resolve($this->fallbackImage);
            });

        return $deferred->promise();
    }

    /**
     * Set the file loader.
     *
     * @param LoaderInterface $loader File loader.
     */
    public function setLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
    }
}
