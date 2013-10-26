<?php

namespace StaticMap;

class Blip {

	protected $latLng;
	protected $image;

    public static function create(LatLng $latlng, $image = null) {
		if (is_null($image) || !file_exists($image)) {
			$image = __DIR__ . DIRECTORY_SEPARATOR . 'Img' . DIRECTORY_SEPARATOR . 'blip.png';
		}

        $instance = new self($latlng, $image);
        
        return $instance;
    }

	public function __construct(LatLng $latlng, $image) {
		$this->latLng = $latlng;
		$this->image = $image;
	}

	public function getLatLng() {
		return $this->latLng;
	}
	public function getImage() {
		return $this->image;
	}
    
}