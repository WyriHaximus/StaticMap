<?php

namespace StaticMap\Tests\Renderer;

class ConvertTest extends \StaticMap\Tests\AbstractRendererTest {
    
    public function setUp() {
        $this->Renderer = new \StaticMap\Renderer\Convert(
            3,
            new \StaticMap\Size(25, 25),
            new \StaticMap\LatLng(71, 111),
            new \StaticMap\Tiles(__DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . '{x}/{y}.png', __DIR__ . DIRECTORY_SEPARATOR . 'Tiles' . DIRECTORY_SEPARATOR . 'black.jpg')
        );
         
        $this->RendererClass = '\StaticMap\Renderer\Convert';
        
        parent::setUp();
    }
    
    public function tearDown() {
        parent::tearDown();
        
        unset($this->Renderer);
    }
    
}