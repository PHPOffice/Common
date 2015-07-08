<?php
/**
 * This file is part of PHPOffice Common
 *
 * PHPOffice Common is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/Common/contributors.
 *
 * @link        https://github.com/PHPOffice/Common
 * @copyright   2009-2014 PHPOffice Common contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\Common;

/**
 * Text
 */
class Text
{
    /**
     * Control characters array
     *
     * @var string[]
     */
    private static $controlCharacters = array();

    /**
     * Build control characters array
     */
    private static function buildControlCharacters()
    {
        for ($i = 0; $i <= 19; ++$i) {
            if ($i != 9 && $i != 10 && $i != 13) {
                $find                            = '_x' . sprintf('%04s', strtoupper(dechex($i))) . '_';
                $replace                         = chr($i);
                self::$controlCharacters[$find] = $replace;
            }
        }
    }

    /**
     * Convert from PHP control character to OpenXML escaped control character
     *
     * Excel 2007 team:
     * ----------------
     * That's correct, control characters are stored directly in the shared-strings table.
     * We do encode characters that cannot be represented in XML using the following escape sequence:
     * _xHHHH_ where H represents a hexadecimal character in the character's value...
     * So you could end up with something like _x0008_ in a string (either in a cell value (<v>)
     * element or in the shared string <t> element.
     *
     * @param  string $value Value to escape
     * @return string
     */
    public static function controlCharacterPHP2OOXML($value = '')
    {
        if (empty(self::$controlCharacters)) {
            self::buildControlCharacters();
        }

        return str_replace(array_values(self::$controlCharacters), array_keys(self::$controlCharacters), $value);
    }
    
    /**
     * Return a number formatted for being integrated in xml files
     * @param float $number
     * @param integer $decimals
     */
    public static function numberFormat($number, $decimals)
    {
        return number_format($number, $decimals, '.', '');
    }
    
    /**
     * @param int $dec
     * @link http://stackoverflow.com/a/7153133/2235790
     * @author velcrow
     */
    public static function chr($dec)
    {
        if ($dec<=0x7F) {
            return chr($dec);
        }
        if ($dec<=0x7FF) {
            return chr(($dec>>6)+192).chr(($dec&63)+128);
        }
        if ($dec<=0xFFFF) {
            return chr(($dec>>12)+224).chr((($dec>>6)&63)+128).chr(($dec&63)+128);
        }
        if ($dec<=0x1FFFFF) {
            return chr(($dec>>18)+240).chr((($dec>>12)&63)+128).chr((($dec>>6)&63)+128).chr(($dec&63)+128);
        }
        return '';
    }
}
