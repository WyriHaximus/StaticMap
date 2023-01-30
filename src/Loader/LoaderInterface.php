<?php

declare(strict_types=1);

/*
 * This file is part of StaticMap and 90% based on \Imagine\Image\Point.
 *
 * (c) 2014 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap\Loader;

use React\Promise\PromiseInterface;

interface LoaderInterface
{
    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     */
    public function addImage(string $url): PromiseInterface;

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     */
    public function imageExists(string $url): PromiseInterface;

    /**
     * Do nothing or execute the operations depending on the implementation.
     */
    public function run(): void;
}
