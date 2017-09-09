<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

use Clue\React\Buzz\Browser;
use React\EventLoop\LoopInterface;
use function React\Promise\resolve;
use WyriHaximus\React\Guzzle\HttpClientAdapter;
use WyriHaximus\StaticMap\Loader\Async;

class AsyncTest extends AbstractLoaderTest
{

    public function setUp()
    {
        parent::setUp();

        $this->loader = new Async();
    }

    public function tearDown()
    {
        unset($this->loader);

        parent::tearDown();
    }

    public function testAddRemoteImagePromise()
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->addImage('http://example.com/black.jpg')
        );
    }

    public function testClient()
    {
        $url = 'http://example.com/black.jpg';

        $loop = $this->prophesize(LoopInterface::class);
        $client = $this->prophesize(Browser::class);
        $client->get($url)->shouldBeCalled()->willReturn(resolve('foo:bar'));

        (new Async($loop->reveal(), $client->reveal()))->addImage($url);
    }
}
