<?php

namespace Modules\Product\Models;

use App\Base\Model;
use Base\App\Casts\Storage\AsImage;
use Base\App\Observers\CompanyIdObserver;
use Base\App\Observers\CreatorObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Company\Models\Company;
use Modules\User\Models\User;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'wasyt_new.product_brand';

    protected $casts = [
        'image' => AsImage::class . ':widen_100|widen_500',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

    protected static function booted(): void
    {
        self::observe([
            CreatorObserver::class,
            CompanyIdObserver::class,
        ]);
    }
}
