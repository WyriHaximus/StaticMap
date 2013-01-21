<?php

namespace StaticMap\Tests;

class SizeTest extends \PHPUnit_Framework_TestCase {
    
    public function testConstructor() {
        $Size = new \StaticMap\Size(35, 45);
        
        $this->assertEquals(35, $Size->getWidth());
        $this->assertEquals(45, $Size->getHeight());
    }
    
    public function testGetter() {
        $Size = new \StaticMap\Size(35, 45);
        
        $Size->setWidth(55);
        $Size->setHeight(65);
        
        $this->assertEquals(55, $Size->getWidth());
        $this->assertEquals(65, $Size->getHeight());
    }
    
    public function testInRange() {
        $Size = new \StaticMap\Size(35, 45, array(
            'begin' => 25,
            'end' => 2500,
        ));
        
        for ($i = 25; $i <= 2500; $i++) {
            $Size->setWidth($i);
            $Size->setHeight($i);

            $this->assertEquals($i, $Size->getWidth());
            $this->assertEquals($i, $Size->getHeight());
        }
    }
    
    public function testOutRange() {
        $Size = new \StaticMap\Size(15, 3501, array(
            'begin' => 25,
            'end' => 2500,
        ));
        
        $this->assertEquals(25, $Size->getWidth());
        $this->assertEquals(2500, $Size->getHeight());
        
        $Size->setWidth(24);
        $Size->setHeight(24);
        $this->assertEquals(25, $Size->getWidth());
        $this->assertEquals(25, $Size->getHeight());
        
        $Size->setWidth(2501);
        $Size->setHeight(2501);
        $this->assertEquals(2500, $Size->getWidth());
        $this->assertEquals(2500, $Size->getHeight());
    }
    
    public function testNonInt() {
        $Size = new \StaticMap\Size('a', 'b');
        
        $this->assertEquals(0, $Size->getWidth());
        $this->assertEquals(0, $Size->getHeight());
    }
    
    public function testNonIntGetter() {
        $Size = new \StaticMap\Size('a', 'b');
        
        $Size->setWidth('c');
        $Size->setHeight('d');
        
        $this->assertEquals(0, $Size->getWidth());
        $this->assertEquals(0, $Size->getHeight());
    }
    
}