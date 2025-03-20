<?php

declare(strict_types=1);

namespace WyriHaximus\StaticMap\Blip;

final readonly class ImageSize
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }
}
