<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Geo;

use Imagine\Image\Box as Base;
use WyriHaximus\StaticMap\Geo\Box\Tiles;
use WyriHaximus\StaticMap\Point;

final readonly class Box
{
    public function __construct(
        public Tiles $tiles,
        public Point $crop,
        public Base $base,
    ) {
    }
}
