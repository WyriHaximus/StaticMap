<?php

/*
 * This file is part of StaticMap.
 *
 * (c) 2013 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StaticMap\Renderer;

/**
 * Gd renderer.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class Gd extends \StaticMap\Renderer implements \StaticMap\RendererInterface {
    
    private $imBase;
    private $imCrop;
    
    protected function createCropImage($size) {
        $this->imCrop = imagecreatetruecolor($size->getWidth(), $size->getHeight());
    }
    
    protected function createBaseImage($size) {
        $this->imBase = imagecreatetruecolor($size->getWidth(), $size->getHeight());
    }
    
    protected function addTile($fileName, $dest) {
        switch(substr($fileName, -3))
        {
            case 'jpg':
                $tileIm = imagecreatefromjpeg($fileName);
                break;
            case 'png':
                $tileIm = imagecreatefrompng($fileName);
                break;
        }
        
        if(isset($tileIm)) {
            imagecopy($this->imCrop, $tileIm, $dest->getWidth(), $dest->getHeight(), 0, 0, \StaticMap\Renderer::tileSize, \StaticMap\Renderer::tileSize);
            imagedestroy($tileIm);
        }
    }
    
    protected function drawBlip($blip) {
        $blipIm = imagecreatefrompng($blip['image']);
        imagecopy($this->imBase, $blipIm, $blip['position']->getWidth(), $blip['position']->getHeight(), 0, 0, $blip['imageSize'][0], $blip['imageSize'][1]);
        imagedestroy($blipIm);
    }
    
    protected function crop($crop, $size) {
        imagecopy($this->imBase, $this->imCrop, 0, 0, $crop->getWidth(), $crop->getHeight(), $size->getWidth(), $size->getHeight());
        imagedestroy($this->imCrop);
    }
    
    public function save($type = 'png', $compression = 9, $fileName = null) {
        switch ($type) {
            case 'png':
                imagepng($this->imBase, $fileName, $compression);
                break;
            case 'jpg':
                imagejpeg($this->imBase, $fileName, $compression);
                break;
        }
    }
    
    public function destroy() {
        imagedestroy($this->imBase);
    }
    
}