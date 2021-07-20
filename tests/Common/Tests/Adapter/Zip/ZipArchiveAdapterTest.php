<?php

namespace Common\Tests\Adapter\Zip;

use PhpOffice\Common\Adapter\Zip\ZipArchiveAdapter;
use PhpOffice\Common\Adapter\Zip\ZipInterface;

class ZipArchiveAdapterTest extends AbstractZipAdapterTest
{
    protected function createAdapter(): ZipInterface
    {
        return new ZipArchiveAdapter();
    }
}
