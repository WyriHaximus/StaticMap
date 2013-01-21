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
 * Convert (imageck commandline tool) renderer.
 *
 * @package StaticMap
 * @author  Cees-Jan Kiewiet <ceesjank@gmail.com>
 */
final class Convert extends \StaticMap\Renderer implements \StaticMap\RendererInterface
{
    private $tiles = array();

    protected function createCropImage($size)
    {
    }

    protected function createBaseImage($size)
    {
    }

    protected function addTile($fileName, $dest)
    {
        $this->tiles[$dest->getHeight()][$dest->getWidth()] = '"' . $fileName . '"';
    }

    protected function crop($crop, $size)
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'StaticMapConvert_' . time() . '_' . md5(uniqid('StaticMapConvert' . time(), true)) . DIRECTORY_SEPARATOR;
        if (!file_exists($this->tmpDir)) {
            @mkdir($this->tmpDir, 0777, true);
        }

        $rows = array();
        foreach ($this->tiles as $row) {
            $rows[] = '\( ' . implode(' ', $row) . ' +append \)';
        }
        $this->tmpFileName = md5(uniqid(implode('_-_', $rows) . time(), true)) . '.png';

        exec('convert ' . implode(' ', $rows) . ' -background none -append   "' . $this->tmpDir . $this->tmpFileName . '"');
        exec('convert "' . $this->tmpDir . $this->tmpFileName . '" -crop ' . $size->getWidth() . 'x' . $size->getHeight() . '+' . $crop->getWidth() . '+' . $crop->getHeight() . ' "' . $this->tmpDir . $this->tmpFileName . '"');
    }

    public function save($type = 'png', $compression = 9, $fileName = null)
    {
        if (!is_null($fileName)) {
            $command = 'convert "' . $this->tmpDir . $this->tmpFileName . '" -quality ' . (int) $compression . ' "' . $fileName . '"';
            exec($command);
        } else {
            $command = 'convert "' . $this->tmpDir . $this->tmpFileName . '" -quality ' . (int) $compression . ' "' . $this->tmpDir . str_replace('.png', '.' . $type, $this->tmpFileName) . '"';
            exec($command);
            echo file_get_contents($this->tmpDir . str_replace('.png', '.' . $type, $this->tmpFileName));
        }
    }

    public function destroy()
    {
        $this->removeDir($this->tmpDir);
    }

    private function removeDir($target)
    {
        $fp = opendir($target);
        while (false !== $file = readdir($fp)) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            if (is_dir($target . DIRECTORY_SEPARATOR . $file)) {
                self::removeDir($target . DIRECTORY_SEPARATOR . $file);
            } else {
                unlink($target . DIRECTORY_SEPARATOR . $file);
            }
        }
        closedir($fp);
        rmdir($target);
    }

}
