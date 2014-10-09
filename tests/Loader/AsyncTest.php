<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

use WyriHaximus\React\Guzzle\HttpClientAdapter;
use WyriHaximus\StaticMap\Loader\Async;

class AsyncTest extends AbstractLoaderTest {

    public function setUp() {
        parent::setUp();

        $this->loader = new Async();
    }

    public function tearDown() {
        unset($this->loader);

        parent::tearDown();
    }

    public function testAddRemoteImagePromise() {
        $this->assertInstanceOf('React\Promise\PromiseInterface', $this->loader->addImage('http://example.com/black.jpg'));
    }

    public function testClient() {
        $url = 'http://example.com/black.jpg';
        $ranCallback = false;
        $loop = $this->getMock('React\EventLoop\LoopInterface');
        $response = $this->getMock('GuzzleHttp\Message\Response', [
            'getBody',
        ], [200]);
        $body = $this->getMock('GuzzleHttp\Stream', [
            'getContents',
        ]);
        $client = $this->getMock('GuzzleHttp\Client', [], [
            [
                'adapter' => new HttpClientAdapter($loop),
                'defaults' => [
                    'headers' => [
                        'User-Agent' => 'foo:bar',
                    ],
                ],
            ]
        ]);
        $promise = $this->getMock('React\Promise\PromiseInterface');
        $this->loader = new Async($loop, $client);

        $response->expects($this->once())->method('getBody')->with()->will($this->returnValue($body));
        $body->expects($this->once())->method('getContents')->with()->will($this->returnValue('foo:bar'));
        $client->expects($this->once())->method('get')->with($url)->will($this->returnValue($promise));
        $promise->expects($this->once())->method('then')->with($this->isType('callable'))->will($this->returnCallback(function($callback) use (&$ranCallback, $response) {
            $callback($response);
            $ranCallback = true;
        }));

        $this->loader->addImage($url);

        $this->assertTrue($ranCallback);
    }

}
