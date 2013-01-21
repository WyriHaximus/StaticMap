<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2012 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StaticMap;

/**
 * Simple colorer returning color based on color in image.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class Size {
    
    /**
     * Value for the width
     * @var int
     */
    private $width = 25;
    
    /**
     * Value for the height
     * @var int
     */
    private $height = 25;
    
    /**
     * Range to test values against
     * @var array
     */
    private $range = false;
    
    /**
     * Store Size value's.
     * 
     * All value's will be sanitized and forced between 25 and 2500.
     * 
     * @param int $width Width
     * @param int $height Height
     */
    public function __construct($width, $height, $range = false) {
        if ($range && is_array($range) && isset($range['begin']) && isset($range['end'])) {
            $this->range = array(
                'begin' => $range['begin'],
                'end' => $range['end'],
            );
        }
        
        $this->setWidth($width);
        $this->setHeight($height);
    }
    
    /**
     * Set the value for the width and returns the stored value
     * @param type $width 
     * @return int 
     */
    public function setWidth($width) {
        $this->width = $this->sanitized($width);
        return $this->width;
    }
    
    /**
     * Returns the value for the color red
     * @return int 
     */
    public function getWidth() {
        return $this->width;
    }
    
    /**
     * Set the value for the height and returns the stored value
     * @param type $height 
     * @return int 
     */
    public function setHeight($height) {
        $this->height = $this->sanitized($height);
        return $this->height;
    }
    
    /**
     * Returns the value for the height
     * @return int 
     */
    public function getHeight() {
        return $this->height;
    }
    
    /**
     * Sanitize value to be int and in a range if passed into the constructor.
     * 
     * @param int $value Value to be sanitized
     * @return int Sanitized and correctly forced value 
     */
    private function sanitized($int) {
        $int = intval($int);
        
        if ($this->range) {
            if ($int > $this->range['end']) {
                $int = $this->range['end'];
            } elseif ($int < $this->range['begin']) {
                $int = $this->range['begin'];
            }
        }
        
        return $int;
    }

}