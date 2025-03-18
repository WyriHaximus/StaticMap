<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Geo\Box;

use WyriHaximus\StaticMap\Point;

final readonly class Tiles
{
    public function __construct(
        public Point $start,
        public Point $stop,
    ) {
    }
}
