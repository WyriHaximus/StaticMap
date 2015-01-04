<?php

/*
 * This file is part of StaticMap and 90% based on \Imagine\Image\Point.
 *
 * (c) 2014 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap\Loader;

use React\Promise\FulfilledPromise;
use React\Promise\RejectedPromise;

/**
 * Class Simple
 *
 * @package WyriHaximus\StaticMap\Loader
 */
class Simple implements LoaderInterface
{
    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     *
     * @return FulfilledPromise
     */
    public function addImage($url)
    {
        return new FulfilledPromise(file_get_contents($url));
    }

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     *
     * @return FulfilledPromise|RejectedPromise
     */
    public function imageExists($url)
    {
        if (file_exists($url)) {
            return new FulfilledPromise();
        }

        return new RejectedPromise();
    }

    /**
     * Nothing to do.
     *
     * @return void
     */
    public function run()
    {
    }
}
