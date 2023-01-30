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

use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;

use function file_exists;
use function file_get_contents;
use function React\Promise\resolve;

final class Simple implements LoaderInterface
{
    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     */
    public function addImage(string $url): PromiseInterface
    {
        return resolve(file_get_contents($url));
    }

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     */
    public function imageExists(string $url): PromiseInterface
    {
        if (file_exists($url)) {
            return new FulfilledPromise();
        }

        return new RejectedPromise();
    }

    /**
     * Nothing to do.
     */
    public function run(): void
    {
    }
}
