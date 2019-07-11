<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests\Loader;

use WyriHaximus\StaticMap\Loader\Simple;

class SimpleTest extends AbstractLoaderTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->loader = new Simple();
    }

    public function tearDown(): void
    {
        unset($this->loader);

        parent::tearDown();
    }
}
