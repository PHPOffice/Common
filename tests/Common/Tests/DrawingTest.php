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

use PhpOffice\Common\Drawing;

/**
 * Test class for IOFactory
 *
 * @coversDefaultClass PhpOffice\Common\IOFactory
 */
class DrawingTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function testPixelsCentimeters()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToCentimeters());
        $this->assertEquals($value / Drawing::DPI_96 * 2.54, Drawing::pixelsToCentimeters($value));
        $this->assertEquals(0, Drawing::centimetersToPixels());
        $this->assertEquals($value / 2.54 * Drawing::DPI_96, Drawing::centimetersToPixels($value));
    }
    
    /**
     */
    public function testPointsCentimeters()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pointsToCentimeters());
        $this->assertEquals($value * 1.333333333 / Drawing::DPI_96 * 2.54, Drawing::pointsToCentimeters($value));
    }
    
    /**
     */
    public function testPixelsEMU()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToEmu());
        $this->assertEquals(round($value*9525), Drawing::pixelsToEmu($value));
        $this->assertEquals(0, Drawing::emuToPixels());
        $this->assertEquals(round($value/9525), Drawing::emuToPixels($value));
    }

    /**
     */
    public function testPixelsPoints()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToPoints());
        $this->assertEquals($value*0.67777777, Drawing::pixelsToPoints($value));
        $this->assertEquals(0, Drawing::pointsToPixels());
        $this->assertEquals($value* 1.333333333, Drawing::pointsToPixels($value));
    }

    /**
     */
    public function testDegreesAngle()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::degreesToAngle());
        $this->assertEquals((int) round($value * 60000), Drawing::degreesToAngle($value));
        $this->assertEquals(0, Drawing::angleToDegrees());
        $this->assertEquals(round($value / 60000), Drawing::angleToDegrees($value));
    }
}
