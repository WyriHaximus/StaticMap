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
     * @param $url
     *
     * @return \React\Promise\Proimise
     */
    public function addImage($url);

    /**
     * @param string $url
     *
     * @return \React\Promise\Proimise
     */
    public function imageExists($url);

    /**
     * @return void
     */
    public function run();

}
