<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\StaticMap\Loader;

use WyriHaximus\StaticMap\Loader\Simple;

final class SimpleTest extends AbstractLoaderTest
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
