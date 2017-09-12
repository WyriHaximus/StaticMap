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

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\FulfilledPromise;
use React\Promise\RejectedPromise;
use React\Stream\ReadableResourceStream;

/**
 * Class Async
 *
 * @package WyriHaximus\StaticMap\Loader
 */
class Async implements LoaderInterface
{
    /**
     * Event loop.
     *
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var Browser
     */
    protected $client;

    /**
     * @param LoopInterface $loop
     * @param Browser $client
     */
    public function __construct(LoopInterface $loop = null, Browser $client = null)
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
     *
     * @return \React\Promise\Proimise|\React\Promise\Promise
     */
    public function addImage($url)
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
     *
     * @return \React\Promise\Promise
     */
    protected function readRemoteFile($url)
    {
        return $this->client->get($url)->then(
            function (ResponseInterface $response) {
                return $response->getBody()->getContents();
            }
        );
    }

    /**
     * Read local image contents.
     *
     * @param string $url Image filename.
     *
     * @return \React\Promise\Promise
     */
    protected function readLocalFile($url)
    {
        $deferred = new Deferred();

        $readStream = fopen($url, 'r+');

        $buffer = '';
        $read = new ReadableResourceStream($readStream, $this->loop);
        $read->on(
            'data',
            function ($data) use (&$buffer) {
                $buffer .= $data;
            }
        );
        $read->on(
            'end',
            function () use ($deferred, &$buffer) {
                $deferred->resolve($buffer);
            }
        );

        return $deferred->promise();
    }

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     *
     * @return FulfilledPromise|\React\Promise\Proimise|RejectedPromise
     */
    public function imageExists($url)
    {
        if (file_exists($url)) {
            return new FulfilledPromise();
        }

        return new RejectedPromise();
    }

    /**
     * Run the event loop and process the assigned operations.
     *
     * @return void
     */
    public function run()
    {
        $this->loop->run();
    }
}
