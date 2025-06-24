<?php

namespace Modules\Product\Search;

use Base\App\Base\Search;
use Base\App\Search\Enums\SearchFilterConditionEnum;
use Base\App\Search\Enums\SearchFilterTypeEnum;
use Base\App\Search\Enums\SearchSortTypeEnum;

class BrandSearch extends Search
{
    public array $relations = [
        'creator.profile',
        'company',
    ];

    public array $filters = [
        'id' => SearchFilterTypeEnum::EQUAL_RAW,
        'creator_id' => SearchFilterTypeEnum::EQUAL_RAW,
        'company_id' => SearchFilterTypeEnum::EQUAL_RAW,
        'name' => SearchFilterTypeEnum::LIKE,
        'created_at' => SearchFilterTypeEnum::BETWEEN_DATE,
    ];

    public array $sortings = [
        'id' => SearchSortTypeEnum::RAW,
    ];
}
