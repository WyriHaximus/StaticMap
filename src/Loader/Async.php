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

use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Http\Browser;
use React\Promise\Deferred;
use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;
use React\Stream\ReadableResourceStream;

use function file_exists;
use function filter_var;
use function fopen;

use const FILTER_VALIDATE_URL;

final class Async implements LoaderInterface
{
    /**
     * Event loop.
     */
    protected LoopInterface $loop;

    protected Browser $client;

    public function __construct(?LoopInterface $loop = null, ?Browser $client = null)
    {
        if ($loop === null) {
            $loop = Factory::create();
        }

        $this->loop = $loop;

        if ($client === null) {
            $client = new Browser($loop);
        }

        $this->client = $client;
    }

    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     */
    public function addImage(string $url): PromiseInterface
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->readRemoteFile($url);
        }

        return $this->readLocalFile($url);
    }

    /**
     * Read remote image contents.
     *
     * @param string $url Image URL.
     */
    protected function readRemoteFile(string $url): PromiseInterface
    {
        return $this->client->get($url)->then(
            static function (ResponseInterface $response) {
                return $response->getBody()->getContents();
            }
        );
    }

    /**
     * Read local image contents.
     *
     * @param string $url Image filename.
     */
    protected function readLocalFile(string $url): PromiseInterface
    {
        $deferred = new Deferred();

        $readStream = fopen($url, 'r+');

        $buffer = '';
        $read   = new ReadableResourceStream($readStream, $this->loop);
        $read->on(
            'data',
            static function ($data) use (&$buffer): void {
                $buffer .= $data;
            }
        );
        $read->on(
            'end',
            static function () use ($deferred, &$buffer): void {
                $deferred->resolve($buffer);
            }
        );

        return $deferred->promise();
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
     * Run the event loop and process the assigned operations.
     */
    public function run(): void
    {
        $this->loop->run();
    }
}
