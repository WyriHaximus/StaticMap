<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Tests\Loader;

use Mockery;
use PHPUnit\Framework\Attributes\Test;
use React\Http\Browser;
use React\Http\Message\Response;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\StaticMap\Loader\Async;

use function React\Async\await;
use function React\Promise\resolve;

final class AsyncTest extends AsyncTestCase
{
    #[Test]
    public function client(): void
    {
        $url = 'http://example.com/black.jpg';

        $client = Mockery::mock(Browser::class);
        $client->shouldReceive('get')->with($url)->andReturn(resolve(new Response(body: 'foo:bar')));

        $image = await(new Async($client)->addImage($url));
        self::assertSame('foo:bar', $image);
    }
}
