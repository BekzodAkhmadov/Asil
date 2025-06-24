<?php

namespace Http\Admin\Product\Controllers;

use Base\App\Http\Controllers\ResourceController;
use Http\Admin\Product\Requests\BrandRequest;
use Illuminate\Http\JsonResponse;

use Modules\Product\Models\Brand;
use Modules\Product\Search\BrandSearch;

class BrandController extends ResourceController
{
    public function __construct()
    {
        parent::__construct(
            model: new Brand(),
            search: new BrandSearch(),
            formRequestClass: BrandRequest::class,
        );
    }
}
