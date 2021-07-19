<?php

namespace Common\Tests\Adapter\Zip;

use PhpOffice\Common\Adapter\Zip\ZipArchiveAdapter;

class ZipArchiveAdapterTest extends AbstractZipAdapterTest
{
    protected function createAdapter()
    {
        return new ZipArchiveAdapter();
    }
}
