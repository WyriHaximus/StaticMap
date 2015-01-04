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

/**
 * Interface LoaderInterface
 *
 * @package WyriHaximus\StaticMap\Loader
 */
interface LoaderInterface
{
    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     *
     * @return \React\Promise\Proimise
     */
    public function addImage($url);

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     *
     * @return \React\Promise\Proimise
     */
    public function imageExists($url);

    /**
     * Do nothing or execute the operations depending on the implementation.
     *
     * @return void
     */
    public function run();
}
