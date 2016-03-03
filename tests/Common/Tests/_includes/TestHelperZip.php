<?php
namespace PhpOffice\Common\Tests;

class TestHelperZip
{
    static public function assertFileExists($fileZip, $path)
    {
        $oZip = new \ZipArchive;
        if ($oZip->open($fileZip) !== true) {
            return false;
        }
        if($oZip->statName($path) === false) {
            return false;
        }
        return true;
    }

    static public function assertFileContent($fileZip, $path, $content)
    {
        $oZip = new \ZipArchive;
        if ($oZip->open($fileZip) !== true) {
            return false;
        }
        $zipFileContent = $oZip->getFromName($path);
        if ($zipFileContent === false) {
            return false;
        }
        if ($zipFileContent != $content) {
            return false;
        }
        return true;
    }
}