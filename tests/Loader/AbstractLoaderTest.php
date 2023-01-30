<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap\Loader;

use WyriHaximus\TestUtilities\TestCase;

use function dirname;

use const DIRECTORY_SEPARATOR;

abstract class AbstractLoaderTest extends TestCase
{
    public function testInterface(): void
    {
        $this->assertInstanceOf('WyriHaximus\StaticMap\Loader\LoaderInterface', $this->loader);
    }

    public function testAddImagePromise(): void
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->addImage(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );
    }

    public function testImageExistsPromise(): void
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->imageExists(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );
    }
}
