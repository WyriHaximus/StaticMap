StaticMap
=========

[![Build Status](https://travis-ci.org/WyriHaximus/StaticMap.png)](https://travis-ci.org/WyriHaximus/StaticMap)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/StaticMap/v/stable.png)](https://packagist.org/packages/WyriHaximus/StaticMap)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/StaticMap/downloads.png)](https://packagist.org/packages/WyriHaximus/StaticMap)
[![Coverage Status](https://coveralls.io/repos/WyriHaximus/StaticMap/badge.png)](https://coveralls.io/r/WyriHaximus/StaticMap)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/WyriHaximus/staticmap/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

Static Google Maps clone in PHP

## Getting started ##

#### 1. Requirements ####

This plugin depends on the following plugins and libraries and are pulled in by composer later on:

- `ext-gd`

### 2. Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `~`.

```
composer require wyrihaximus/staticmap 
```

### 3. Example ###

```php
<?php

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use WyriHaximus\StaticMap;

require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$width = 256;
$height = 256;
$zoom = 7;
$latitude = 0;
$longitude = 0;

$renderer = new StaticMap\Renderer(
    new Imagine(),
    $zoom,
    new Box($width, $height),
    new StaticMap\LatLng($latitude, $longitude),
    new StaticMap\Tiles('http://example.com/tiles/' . $zoom . '/{x}/{y}.png')
);

header('Content-Type: image/png');
echo $renderer->generate()->get('png', array(
    'quality' => 9,
));
```
