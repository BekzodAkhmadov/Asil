<?php

namespace Http\Admin\Product\Tests\Brand;

class ShowTest extends _TestCase
{
    public function test_success(): void
    {
        $this->sendRequest(
            path: '1',
        );
    }
}
