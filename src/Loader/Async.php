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
use React\EventLoop\Loop;
use React\Http\Browser;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Stream\ReadableResourceStream;
use RuntimeException;

use function file_exists;
use function filter_var;
use function fopen;
use function React\Promise\resolve;

use const FILTER_VALIDATE_URL;

final class Async implements LoaderInterface
{
    private Browser $client;

    public function __construct(Browser|null $client = null)
    {
        if ($client === null) {
            $client = new Browser();
        }

        $this->client = $client;
    }

    /**
     * Load file from $url.
     *
     * @param string $url Image URL.
     *
     * @return PromiseInterface<string>
     */
    public function addImage(string $url): PromiseInterface
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return $this->readRemoteFile($url);
        }

        return $this->readLocalFile($url);
    }

    /**
     * Read remote image contents.
     *
     * @param string $url Image URL.
     *
     * @return PromiseInterface<string>
     */
    private function readRemoteFile(string $url): PromiseInterface
    {
        return $this->client->get($url)->then(
            static function (ResponseInterface $response): string {
                return $response->getBody()->getContents();
            },
        );
    }

    /**
     * Read local image contents.
     *
     * @param string $url Image filename.
     *
     * @return PromiseInterface<string>
     */
    private function readLocalFile(string $url): PromiseInterface
    {
        /** @var Deferred<string> $deferred */
        $deferred = new Deferred();

        $readStream = fopen($url, 'r');
        if ($readStream === false) {
            throw new RuntimeException('Unable to open file');
        }

        $buffer = '';
        $read   = new ReadableResourceStream($readStream);
        $read->on(
            'data',
            static function ($data) use (&$buffer): void {
                $buffer .= $data;
            },
        );
        $read->on(
            'end',
            static function () use ($deferred, &$buffer): void {
                $deferred->resolve($buffer);
            },
        );

        return $deferred->promise();
    }

    /**
     * Check if $url exists.
     *
     * @param string $url Image URL.
     *
     * @return PromiseInterface<bool>
     */
    public function imageExists(string $url): PromiseInterface
    {
        return resolve(file_exists($url));
    }

    /**
     * Run the event loop and process the assigned operations.
     */
    public function run(): void
    {
        Loop::run();
    }
}
