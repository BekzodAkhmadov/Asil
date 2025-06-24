<?php

namespace Http\Admin\Product\Tests\Brand;

use Base\App\Testing\Feature\Traits\SearchFeatureTestTrait;
use Modules\Product\Search\BrandSearch;

class IndexTest extends _TestCase
{
    use SearchFeatureTestTrait;

    public string $searchClass = BrandSearch::class;

    public function test_available_filters(): void
    {
        $this->sendRequestWithFilters([
            'id' => 1,
            'creator_id' => 1,
            'company_id' => 1,
            'name' => 'brand',
            'created_at' => [
                date('Y-m-d', strtotime('-1 day')),
                date('Y-m-d'),
            ],
        ]);
    }
}
