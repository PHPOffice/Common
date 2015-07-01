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

namespace PhpOffice\Common\Tests;

use PhpOffice\Common\String;

/**
 * Test class for String
 *
 * @coversDefaultClass PhpOffice\Common\String
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function testControlCharacters()
    {
        $this->assertEquals('', String::controlCharacterPHP2OOXML());
        $this->assertEquals('aeiou', String::controlCharacterPHP2OOXML('aeiou'));
        $this->assertEquals('àéîöù', String::controlCharacterPHP2OOXML('àéîöù'));

        $value = rand(0, 8);
        $this->assertEquals('_x'.sprintf('%04s', strtoupper(dechex($value))).'_', String::controlCharacterPHP2OOXML(chr($value)));
    }
    
    public function testNumberFormat()
    {
        $this->assertEquals('2.1', String::numberFormat('2.06', 1));
        $this->assertEquals('2.1', String::numberFormat('2.12', 1));
        $this->assertEquals('1234', String::numberFormat(1234, 1));
    }
    
    public function testChr()
    {
        $this->assertEquals('A', String::chr(65));
        $this->assertEquals('A', String::chr(0x41));
        $this->assertEquals('é', String::chr(233));
        $this->assertEquals('é', String::chr(0xE9));
        $this->assertEquals('⼳', String::chr(12083));
        $this->assertEquals('⼳', String::chr(0x2F33));
        $this->assertEquals('🌃', String::chr(127747));
        $this->assertEquals('🌃', String::chr(0x1F303));
        $this->assertEquals('', String::chr(2097152));
    }
}
