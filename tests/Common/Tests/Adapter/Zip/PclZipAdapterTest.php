<?php

namespace Common\Tests\Adapter\Zip;

use PhpOffice\Common\Adapter\Zip\PclZipAdapter;
use PhpOffice\Common\Adapter\Zip\ZipInterface;

class PclZipAdapterTest extends AbstractZipAdapterTest
{
    protected function createAdapter(): ZipInterface
    {
        return new PclZipAdapter();
    }
}
