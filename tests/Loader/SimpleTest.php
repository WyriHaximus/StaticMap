<?php

namespace WyriHaximus\WyriHaximus\StaticMap\Tests;

use WyriHaximus\StaticMap\Loader\Simple;

class SimpleTest extends AbstractLoaderTest {

    public function setUp() {
        parent::setUp();

        $this->loader = new Simple();
    }

    public function tearDown() {
        unset($this->loader);

        parent::tearDown();
    }

}
