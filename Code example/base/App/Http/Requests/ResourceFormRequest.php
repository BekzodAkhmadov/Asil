<?php

namespace Base\App\Http\Requests;

use Base\App\Base\FormRequest;
use App\Base\Model;
use Base\App\Http\Requests\ResourceFormRequest\DeletableFileFieldsTrait;
use Base\Modules\Seo\Traits\SeoMetaFormRequestTrait;
use Illuminate\Support\Arr;

class ResourceFormRequest extends FormRequest
{
    use DeletableFileFieldsTrait;

    public Model $model;

    public function __construct()
    {
        $this->model ??= request()->route('model') ?? request()->route()->controller->model;
        parent::__construct();
    }

    public function rules(): array
    {
        $rules = parent::rules();

        if (in_array(SeoMetaFormRequestTrait::class, class_uses_recursive($this))) {
            $seoMetaRules = $this->seoMetaRules();
            $locales = array_keys(app('language')->all);

            foreach ($seoMetaRules as $key => $rule) {
                foreach ($locales as $locale) {
                    $rules["$key.$locale"] = $rule;
                }
            }
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (
            $this->model->updated_at &&
            $this->updated_at &&
            $this->updated_at !== $this->model->updated_at->format('Y-m-d H:i:s')
        ) {
            abort(409, __('Данные были изменены другим источником. Обновите страницу, чтобы увидеть изменения.'));
        }
    }

    protected function passedValidation(): void
    {
        parent::passedValidation();

        $this->deletableFileFieldsProcess();

        if (in_array(SeoMetaFormRequestTrait::class, class_uses_recursive($this))) {
            $this->model->fillRelations(
                oneToOne: [
                    'seo_meta_morph' => [
                        'value' => Arr::pull($this->validatedData, 'seo_meta'),
                    ],
                ],
            );
        }
    }
}
