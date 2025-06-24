<?php

namespace Http\Admin\Product\Tests\Brand;

use Base\App\Testing\Feature\Helpers\FormHelper;

class CreateTest extends _TestCase
{
    public function test_success(): void
    {
        $this->sendRequest(
            method: 'POST',
            body: [
                'company_id' => 1,
                'name' => 'Brand 3',
                ...FormHelper::deletableFiles(
                    field: 'image',
                    files: FormHelper::realImage(),
                    oldKeys: [],
                ),
            ],
        );
    }
}
