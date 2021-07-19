<?php

namespace Common\Tests\Adapter\Zip;

use PhpOffice\Common\Adapter\Zip\PclZipAdapter;

class PclZipAdapterTest extends AbstractZipAdapterTest
{
    protected function createAdapter()
    {
        return new PclZipAdapter();
    }
}
