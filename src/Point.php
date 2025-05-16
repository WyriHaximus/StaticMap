<?php

declare(strict_types=1);

/*
 * This file is part of StaticMap and 90% based on \Imagine\Image\Point.
 *
 * (c) 2012 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WyriHaximus\StaticMap;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use InvalidArgumentException;

use function sprintf;

/**
 * The point class
 */
final readonly class Point implements PointInterface
{
    /**
     * Constructs a point of coordinates
     *
     * @throws InvalidArgumentException
     */
    public function __construct(public int $x, public int $y)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * {@inheritDoc}
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * {@inheritDoc}
     */
    public function in(BoxInterface $box)
    {
        return $this->x < $box->getWidth() && $this->y < $box->getHeight();
    }

    /**
     * {@inheritDoc}
     */
    public function move($amount)
    {
        return new Point($this->x + $amount, $this->y + $amount);
    }

    public function __toString(): string
    {
        return sprintf('(%d, %d)', $this->x, $this->y);
    }
}
