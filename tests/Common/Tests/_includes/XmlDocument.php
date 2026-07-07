<?php

/**
 * This file is part of PHPPowerPoint - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPPowerPoint is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPPowerPoint/contributors.
 *
 * @see        https://github.com/PHPOffice/PHPPowerPoint
 *
 * @copyright   2010-2016 PHPPowerPoint contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpPowerpoint\Tests;

use DOMXpath;

/**
 * DOM wrapper class
 */
class XmlDocument
{
    /**
     * Path
     *
     * @var string
     */
    private $path;

    /**
     * \DOMDocument object
     *
     * @var \DOMDocument
     */
    private $dom;

    /**
     * DOMXpath object
     *
     * @var \DOMXpath|null
     */
    private $xpath;

    /**
     * File name
     *
     * @var string
     */
    private $file;

    /**
     * Create new instance
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = realpath($path) ?: '';
    }

    /**
     * Get DOM from file
     *
     * @param string $file
     *
     * @return \DOMDocument
     */
    public function getFileDom(string $file = 'word/document.xml'): \DOMDocument
    {
        if (null !== $this->dom && $file === $this->file) {
            return $this->dom;
        }

        $this->xpath = null;
        $this->file = $file;

        $file = $this->path . '/' . $file;
        $this->dom = new \DOMDocument();
        $this->dom->load($file);

        return $this->dom;
    }

    /**
     * Get node list
     *
     * @param string $path
     * @param string $file
     *
     * @return \DOMNodeList<\DOMNameSpaceNode|\DOMNode>
     */
    public function getNodeList(string $path, string $file = 'word/document.xml'): \DOMNodeList
    {
        if ($this->dom === null || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new \DOMXpath($this->dom);
        }

        $elements = $this->xpath->query($path);

        return $elements === false ? new \DOMNodeList() : $elements;
    }

    /**
     * Get element
     *
     * @param string $path
     * @param string $file
     *
     * @return \DOMNode
     */
    public function getElement(string $path, string $file = 'word/document.xml'): \DOMNode
    {
        $element = $this->getNodeList($path, $file)->item(0);
        if (!$element instanceof \DOMNode) {
            throw new \Exception('Element not found: ' . $path);
        }

        return $element;
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get element attribute
     *
     * @param string $path
     * @param string $attribute
     * @param string $file
     *
     * @return string
     */
    public function getElementAttribute(string $path, string $attribute, string $file = 'word/document.xml'): string
    {
        $element = $this->getElement($path, $file);

        return $element instanceof \DOMElement ? $element->getAttribute($attribute) : '';
    }

    /**
     * Get element attribute
     *
     * @param string $path
     * @param string $attribute
     * @param string $file
     *
     * @return bool
     */
    public function attributeElementExists(string $path, string $attribute, string $file = 'word/document.xml'): bool
    {
        $element = $this->getElement($path, $file);

        return $element instanceof \DOMElement ? $element->hasAttribute($attribute) : false;
    }

    /**
     * Check if element exists
     *
     * @param string $path
     * @param string $file
     *
     * @return bool
     */
    public function elementExists(string $path, string $file = 'word/document.xml'): bool
    {
        $nodeList = $this->getNodeList($path, $file);

        return !($nodeList->length == 0);
    }
}
