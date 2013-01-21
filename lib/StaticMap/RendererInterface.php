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
 * RendererInterface.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
interface RendererInterface {
    
    public function save($type = 'png', $compression = 9, $fileName = null);
    
    public function destroy();
    
}