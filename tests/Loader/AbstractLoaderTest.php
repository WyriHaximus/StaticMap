<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

abstract class AbstractLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function testInterface()
    {
        $this->assertInstanceOf('WyriHaximus\StaticMap\Loader\LoaderInterface', $this->loader);
    }

    public function testAddImagePromise()
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->addImage(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );
    }

    public function testImageExistsPromise()
    {
        $this->assertInstanceOf(
            'React\Promise\PromiseInterface',
            $this->loader->imageExists(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg'
            )
        );
    }
}
