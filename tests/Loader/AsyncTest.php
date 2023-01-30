<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap\Loader;

use Clue\React\Buzz\Browser;
use React\EventLoop\LoopInterface;
use WyriHaximus\StaticMap\Loader\Async;

use function React\Promise\resolve;

final class AsyncTest extends AbstractLoaderTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loader = new Async();
    }

    public function tearDown(): void
    {
        unset($this->loader);

        parent::tearDown();
    }

    public function testAddRemoteImagePromise(): void
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->addImage('http://example.com/black.jpg')
        );
    }

    public function testClient(): void
    {
        $url = 'http://example.com/black.jpg';

        $loop   = $this->prophesize(LoopInterface::class);
        $client = $this->prophesize(Browser::class);
        $client->get($url)->shouldBeCalled()->willReturn(resolve('foo:bar'));

        (new Async($loop->reveal(), $client->reveal()))->addImage($url);
    }
}
