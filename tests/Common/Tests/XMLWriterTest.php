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
 * @copyright   2009-2016 PHPOffice Common contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\Common\Tests;

use PhpOffice\Common\XMLWriter;

/**
 * Test class for XMLWriter
 *
 * @coversDefaultClass PhpOffice\Common\XMLWriter
 */
class XMLWriterTest extends \PHPUnit\Framework\TestCase
{
    /**
     */
    public function testConstruct()
    {
        // Memory
        $object = new XMLWriter();
        $object->startElement('element');
            $object->text('AAA');
        $object->endElement();
        $this->assertEquals('<element>AAA</element>'.chr(10), $object->getData());

        // Disk
        $object = new XMLWriter(XMLWriter::STORAGE_DISK);
        $object->startElement('element');
            $object->text('BBB');
        $object->endElement();
        $this->assertEquals('<element>BBB</element>'.chr(10), $object->getData());
    }

    public function testWriteAttribute()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('element');
        $xmlWriter->writeAttribute('name', 'value');
        $xmlWriter->endElement();

        $this->assertSame('<element name="value"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteAttributeIf()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('element');
        $xmlWriter->writeAttributeIf(true, 'trueAttr', '1');
        $xmlWriter->writeAttributeIf(false, 'falseAttr', '0');
        $xmlWriter->endElement();

        $this->assertSame('<element trueAttr="1"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteElementIf()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->writeElementIf(true, 'trueElement', null, '1');
        $xmlWriter->writeElementIf(false, 'falseElement', null, '0');
        $xmlWriter->writeElementIf(true, 'trueElementAttr', 'trueAttr', '2');
        $xmlWriter->writeElementIf(false, 'falseElementAttr', 'falseAttr', '0');

        $this->assertSame('<trueElement>1</trueElement>' . chr(10)
            . '<trueElementAttr trueAttr="2"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteElementBlockString()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->writeElementBlock('element', 'name', 'value');

        $this->assertSame('<element name="value"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteElementBlockArray()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->writeElementBlock('element', array('name1' => 'value1', 'name2' => 'value2'));

        $this->assertSame('<element name1="value1" name2="value2"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteAttributeShouldWriteFloatValueLocaleIndependent()
    {
        $value = 1.2;

        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('element');
        $xmlWriter->writeAttribute('name', $value);
        $xmlWriter->endElement();

        setlocale(LC_NUMERIC, 'de_DE.UTF-8', 'de');

        $this->assertSame((version_compare(PHP_VERSION, 8) < 0) ? '1,2' : '1.2', (string)$value);
        $this->assertSame('<element name="1.2"/>' . chr(10), $xmlWriter->getData());
    }

    public function testCompatibilityTrue()
    {
        $xmlWriter = new XMLWriter(\PhpOffice\Common\XMLWriter::STORAGE_MEMORY, null, true);
        $xmlWriter->startElement('element1');
        $xmlWriter->startElement('element2');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $this->assertSame('<element1>'
            . '<element2/>'
            . '</element1>', $xmlWriter->getData());
    }

    public function testCompatibilityFalse()
    {
        $xmlWriter = new XMLWriter(\PhpOffice\Common\XMLWriter::STORAGE_MEMORY, null, false);
        $xmlWriter->startElement('element1');
        $xmlWriter->startElement('element2');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $this->assertSame('<element1>' . chr(10)
            . '  <element2/>' . chr(10)
            . '</element1>' . chr(10), $xmlWriter->getData());
    }
}
