<?php

namespace Http\Admin\Product\Tests\Brand;

use Modules\Product\Models\Brand;

class DeleteTest extends _TestCase
{
    public function test_delete(): void
    {
        $this->sendRequest(
            method: 'DELETE',
            path: '2',
        );
    }

    public function test_restore(): void
    {
        Brand::query()->find(2)->delete();

        $this->sendRequest(
            method: 'DELETE',
            path: '2/restore',
        );
    }
}
