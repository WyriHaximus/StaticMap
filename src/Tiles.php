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
use React\Promise\PromiseInterface;
use WyriHaximus\StaticMap\Loader\LoaderInterface;

use function str_replace;

/**
 * Renderer using given Imagine instance.
 */
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
     *
     * @return PromiseInterface<string>
     */
    public function getTile(int $xAxis, int $yAxis): PromiseInterface
    {
        $fileName = str_replace(['{x}', '{y}'], [$xAxis, $yAxis], $this->location);

        /** @var Deferred<string> $deferred */
        $deferred = new Deferred();

        $this->loader->imageExists($fileName)->then(function (bool $exists) use ($deferred, $fileName): void {
            if ($exists === true || $this->fallbackImage === '') {
                $deferred->resolve($fileName);

                return;
            }

            $deferred->resolve($this->fallbackImage);
        });

        return $deferred->promise();
    }

    /**
     * Set the file loader.
     */
    public function setLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
    }
}
