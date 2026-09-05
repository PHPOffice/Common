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
 * @see        https://github.com/PHPOffice/Common
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\Common\Tests\Microsoft;

use PhpOffice\Common\Microsoft\OLERead;
use PhpOffice\Common\Microsoft\OLEWrite;

/**
 * Test class for PhpOffice\Common\Microsoft\OLEWrite
 *
 * @coversDefaultClass \PhpOffice\Common\Microsoft\OLEWrite
 */
class OLEWriteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Files to delete after the test
     *
     * @var string[]
     */
    private $files = [];

    protected function tearDown(): void
    {
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $this->files = [];
    }

    public function testDocumentStartsWithOleIdentifier(): void
    {
        $writer = new OLEWrite();
        $writer->addStream('Pictures', 'content');

        self::assertStringStartsWith(OLERead::IDENTIFIER_OLE, $writer->write());
    }

    public function testDocumentIsMadeOfWholeSectors(): void
    {
        $writer = new OLEWrite();
        $writer->addStream('Pictures', str_repeat('a', 10000));

        self::assertSame(0, strlen($writer->write()) % OLEWrite::SECTOR_SIZE);
    }

    /**
     * Streams below the mini stream cutoff, above it, and empty ones are all read back.
     */
    public function testStreamsAreReadBackByOleRead(): void
    {
        $streams = [
            'Pictures' => str_repeat('small stream ', 10),
            'Current User' => '',
            'PowerPoint Document' => str_repeat('big stream ', 1000),
            chr(5) . 'SummaryInformation' => 'summary',
        ];

        $writer = new OLEWrite();
        foreach ($streams as $name => $content) {
            $writer->addStream($name, $content);
        }

        $reader = new OLERead();
        $reader->read($this->writeToFile($writer->write()));

        foreach ($reader->props as $index => $prop) {
            if ($prop['type'] !== OLEWrite::TYPE_STREAM) {
                continue;
            }
            self::assertArrayHasKey($prop['name'], $streams);
            self::assertSame(strlen($streams[$prop['name']]), $prop['size']);
            self::assertSame(
                $streams[$prop['name']],
                substr((string) $reader->getStream($index), 0, $prop['size'])
            );
        }
    }

    public function testStreamsAreDeclaredInTheDirectory(): void
    {
        $writer = new OLEWrite();
        $writer->addStream('Pictures', 'first');
        $writer->addStream('Current User', 'second');

        $reader = new OLERead();
        $reader->read($this->writeToFile($writer->write()));

        $names = [];
        foreach ($reader->props as $prop) {
            $names[] = $prop['name'];
        }
        sort($names);

        self::assertSame(['Current User', 'Pictures', 'Root Entry'], $names);
    }

    public function testStreamContentIsOverwrittenByTheLastCall(): void
    {
        $writer = new OLEWrite();
        $writer->addStream('Pictures', 'first');
        $writer->addStream('Pictures', 'second');

        $reader = new OLERead();
        $reader->read($this->writeToFile($writer->write()));

        self::assertSame('second', substr((string) $reader->getStream($reader->pictures), 0, 6));
    }

    public function testEmptyStreamNameIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new OLEWrite())->addStream('', 'content');
    }

    public function testTooLongStreamNameIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new OLEWrite())->addStream(str_repeat('a', OLEWrite::MAX_NAME_LENGTH + 1), 'content');
    }

    public function testTooLargeDocumentIsRejected(): void
    {
        $writer = new OLEWrite();
        for ($index = 0; $index < 20; ++$index) {
            $writer->addStream('Stream ' . $index, str_repeat('a', 400 * 1024));
        }

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('OLE document too large to be written.');

        $writer->write();
    }

    /**
     * Write a document to a temporary file and return its name.
     */
    private function writeToFile(string $content): string
    {
        $file = (string) tempnam(sys_get_temp_dir(), 'PhpOfficeCommonOLEWrite');
        file_put_contents($file, $content);
        $this->files[] = $file;

        return $file;
    }
}
