<?php

namespace Http\Admin\Product\Tests\Brand;

use Base\App\Testing\Feature\Helpers\FormHelper;

class UpdateTest extends _TestCase
{
    public function test_with_new_image(): void
    {
        $this->sendRequest(
            method: 'PUT',
            path: '1',
            body: [
                'company_id' => 1,
                'name' => 'Brand 1',
                ...FormHelper::deletableFiles(
                    field: 'image',
                    files: FormHelper::realImage(),
                    oldKeys: [0],
                ),
            ],
        );
    }

    public function test_without_new_image(): void
    {
        $this->sendRequest(
            method: 'PUT',
            path: '1',
            body: [
                'company_id' => 1,
                'name' => 'Brand 1',
                'image_old_keys' => '[0]',
            ],
        );
    }

    public function test_delete_image(): void
    {
        $this->sendRequest(
            method: 'PUT',
            path: '1',
            body: [
                'company_id' => 1,
                'name' => 'Brand 1',
                'image_old_keys' => '[]',
            ],
        );
    }
}
