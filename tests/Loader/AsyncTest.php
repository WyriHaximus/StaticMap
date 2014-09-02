<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

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

}
