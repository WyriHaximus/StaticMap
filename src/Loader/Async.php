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

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\FulfilledPromise;
use React\Promise\RejectedPromise;
use React\Stream\Stream;
use WyriHaximus\React\Guzzle\HttpClientAdapter;

/**
 * Class Async
 *
 * @package WyriHaximus\StaticMap\Loader
 */
class Async implements LoaderInterface
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    protected $loop;

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop = null)
    {
        if ($loop === null) {
            $loop = Factory::create();
        }

        $this->loop = $loop;
    }

    /**
     * @param string $url
     *
     * @return \React\Promise\Proimise|\React\Promise\Promise
     */
    public function addImage($url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->readRemoteFile($url);
        }
        return $this->readLocalFile($url);
    }

    /**
     * @param string $url
     *
     * @return \React\Promise\Promise
     */
    protected function readRemoteFile($url)
    {
        $deferred = new Deferred();
        $client = new Client([
            'adapter' => new HttpClientAdapter($this->loop),
        ]);
        $client->get($url)->then(function (Response $response) use ($deferred) {
            $deferred->resolve($response->getBody()->getContents());
        });
        return $deferred->promise();
    }

    /**
     * @param string $url
     *
     * @return \React\Promise\Promise
     */
    protected function readLocalFile($url)
    {
        $deferred = new Deferred();

        $readStream = fopen($url, 'r');
        stream_set_blocking($readStream, 0);

        $buffer = '';
        $read = new Stream($readStream, $this->loop);
        $read->on('data', function ($data) use (&$buffer) {
            $buffer .= $data;
        });
        $read->on('end', function () use ($deferred, &$buffer) {
            $deferred->resolve($buffer);
        });

        return $deferred->promise();
    }

    /**
     * @param string $url
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

    public function run()
    {
        $this->loop->run();
    }

}
