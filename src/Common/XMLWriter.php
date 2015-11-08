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
 * XMLWriter
 *
 * @method bool endElement()
 * @method mixed flush(bool $empty = null)
 * @method bool openMemory()
 * @method string outputMemory(bool $flush = null)
 * @method bool setIndent(bool $indent)
 * @method bool startDocument(string $version = 1.0, string $encoding = null, string $standalone = null)
 * @method bool startElement(string $name)
 * @method bool text(string $content)
 * @method bool writeAttribute(string $name, mixed $value)
 * @method bool writeCData(string $content)
 * @method bool writeComment(string $content)
 * @method bool writeElement(string $name, string $content = null)
 * @method bool writeRaw(string $content)
 */
class XMLWriter
{
    /** Temporary storage method */
    const STORAGE_MEMORY = 1;
    const STORAGE_DISK = 2;

    /**
     * Internal XMLWriter
     *
     * @var \XMLWriter
     */
    private $xmlWriter;

    /**
     * Temporary filename
     *
     * @var string
     */
    private $tempFileName = '';

    /**
     * Create a new \PhpOffice\PhpPowerpoint\Shared\XMLWriter instance
     *
     * @param int $pTemporaryStorage Temporary storage location
     * @param string $pTemporaryStorageDir Temporary storage folder
     */
    public function __construct($pTemporaryStorage = self::STORAGE_MEMORY, $pTemporaryStorageDir = './', $compatibility = false)
    {
        // Create internal XMLWriter
        $this->xmlWriter = new \XMLWriter();

        // Open temporary storage
        if ($pTemporaryStorage == self::STORAGE_MEMORY) {
            $this->xmlWriter->openMemory();
        } else {
            // Create temporary filename
            $this->tempFileName = @tempnam($pTemporaryStorageDir, 'xml');

            // Open storage
            $this->xmlWriter->openUri($this->tempFileName);
        }

        if ($compatibility) {
            $this->xmlWriter->setIndent(false);
            $this->xmlWriter->setIndentString('');
        } else {
            $this->xmlWriter->setIndent(true);
            $this->xmlWriter->setIndentString('  ');
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Desctruct XMLWriter
        unset($this->xmlWriter);

        // Unlink temporary files
        if ($this->tempFileName != '') {
            if (@unlink($this->tempFileName) === false) {
                throw new \Exception('The file '.$this->tempFileName.' could not be deleted.');
            }
        }
    }

    /**
     * Catch function calls (and pass them to internal XMLWriter)
     *
     * @param mixed $function
     * @param mixed $args
     */
    public function __call($function, $args)
    {
        try {
            if (@call_user_func_array(array($this->xmlWriter, $function), $args) === false) {
                throw new \Exception('The method '.$function.' doesn\'t exist.');
            }
        } catch (\Exception $ex) {
            // Do nothing!
        }
    }

    /**
     * Get written data
     *
     * @return string
     */
    public function getData()
    {
        if ($this->tempFileName == '') {
            return $this->xmlWriter->outputMemory(true);
        } else {
            $this->xmlWriter->flush();
            return file_get_contents($this->tempFileName);
        }
    }


    /**
     * Write simple element and attribute(s) block
     *
     * There are two options:
     * 1. If the `$attributes` is an array, then it's an associative array of attributes
     * 2. If not, then it's a simple attribute-value pair
     *
     * @param string $element
     * @param string|array $attributes
     * @param string $value
     * @return void
     */
    public function writeElementBlock($element, $attributes, $value = null)
    {
        $this->xmlWriter->startElement($element);
        if (!is_array($attributes)) {
            $attributes = array($attributes => $value);
        }
        foreach ($attributes as $attribute => $value) {
            $this->xmlWriter->writeAttribute($attribute, $value);
        }
        $this->xmlWriter->endElement();
    }

    /**
     * Write element if ...
     *
     * @param bool $condition
     * @param string $element
     * @param string $attribute
     * @param mixed $value
     * @return void
     */
    public function writeElementIf($condition, $element, $attribute = null, $value = null)
    {
        if ($condition == true) {
            if (is_null($attribute)) {
                $this->xmlWriter->writeElement($element, $value);
            } else {
                $this->xmlWriter->startElement($element);
                $this->xmlWriter->writeAttribute($attribute, $value);
                $this->xmlWriter->endElement();
            }
        }
    }

    /**
     * Write attribute if ...
     *
     * @param bool $condition
     * @param string $attribute
     * @param mixed $value
     * @return void
     */
    public function writeAttributeIf($condition, $attribute, $value)
    {
        if ($condition == true) {
            $this->xmlWriter->writeAttribute($attribute, $value);
        }
    }
}
