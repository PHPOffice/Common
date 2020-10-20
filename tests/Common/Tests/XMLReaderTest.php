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
 * @copyright   2009-2017 PHPOffice Common contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\Common\Tests;

use PhpOffice\Common\XMLReader;

/**
 * Test class for XMLReader
 *
 * @coversDefaultClass PhpOffice\Common\XMLReader
 */
class XMLReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test reading XML from string
     */
    public function testDomFromString()
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element attr="test"><child attr="subtest">AAA</child></element>');

        $this->assertTrue($reader->elementExists('/element/child'));
        $this->assertEquals('AAA', $reader->getElement('/element/child')->textContent);
        $this->assertEquals('AAA', $reader->getValue('/element/child'));
        $this->assertEquals('test', $reader->getAttribute('attr', $reader->getElement('/element')));
        $this->assertEquals('subtest', $reader->getAttribute('attr', $reader->getElement('/element'), 'child'));
    }

    /**
     * Test reading XML from zip
     */
    public function testDomFromZip()
    {
        $pathResources = PHPOFFICE_COMMON_TESTS_BASE_DIR.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;

        $reader = new XMLReader();
        $reader->getDomFromZip($pathResources. 'reader.zip', 'test.xml');

        $this->assertTrue($reader->elementExists('/element/child'));

        $this->assertFalse($reader->getDomFromZip($pathResources. 'reader.zip', 'non_existing_xml_file.xml'));
    }

    /**
     * Test that read from non existing archive throws exception
     *
     */
    public function testThrowsExceptionOnNonExistingArchive()
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException('Exception');
        } else {
            $this->setExpectedException('Exception');
        }
        $pathResources = PHPOFFICE_COMMON_TESTS_BASE_DIR.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;

        $reader = new XMLReader();
        $reader->getDomFromZip($pathResources. 'readers.zip', 'test.xml');
    }

    /**
     * Test elements count
     */
    public function testCountElements()
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element attr="test"><child>AAA</child><child>BBB</child></element>');

        $this->assertEquals(2, $reader->countElements('/element/child'));
    }

    /**
     * Test read non existing elements
     */
    public function testReturnNullOnNonExistingNode()
    {
        $reader = new XMLReader();
        $this->assertEmpty($reader->getElements('/element/children'));
        $reader->getDomFromString('<element><child>AAA</child></element>');

        $this->assertNull($reader->getElement('/element/children'));
        $this->assertNull($reader->getValue('/element/children'));
    }

    /**
     * Test that xpath fails if custom namespace is not registered
     */
    public function testShouldThrowExceptionIfNamespaceIsNotKnown()
    {
        try {
            $reader = new XMLReader();
            $reader->getDomFromString('<element><test:child xmlns:test="http://phpword.com/my/custom/namespace">AAA</test:child></element>');

            $this->assertTrue($reader->elementExists('/element/test:child'));
            $this->assertEquals('AAA', $reader->getElement('/element/test:child')->textContent);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test reading XML with manually registered namespace
     */
    public function testShouldParseXmlWithCustomNamespace()
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element><test:child xmlns:test="http://phpword.com/my/custom/namespace">AAA</test:child></element>');
        $reader->registerNamespace('test', 'http://phpword.com/my/custom/namespace');

        $this->assertTrue($reader->elementExists('/element/test:child'));
        $this->assertEquals('AAA', $reader->getElement('/element/test:child')->textContent);
    }

    /**
     * Test that xpath fails if custom namespace is not registered
     *
     */
    public function testShouldThowExceptionIfTryingToRegisterNamespaceBeforeReadingDoc()
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException('InvalidArgumentException');
        } else {
            $this->setExpectedException('InvalidArgumentException');
        }
        $reader = new XMLReader();
        $reader->registerNamespace('test', 'http://phpword.com/my/custom/namespace');
    }
}
