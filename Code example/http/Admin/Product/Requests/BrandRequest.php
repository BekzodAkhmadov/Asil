<?php

namespace Http\Admin\Product\Requests;

use Base\App\Helpers\Validation\ValidationFileRulesHelper;
use Base\App\Http\Requests\ResourceFormRequest;
use Base\App\Rules\ExistsWithOldRule;
use Base\App\Rules\UniqueRule;
use Illuminate\Validation\Rule;
use Modules\Company\Models\Company;

class BrandRequest extends ResourceFormRequest
{
    protected array $imagesSavePaths = [
        'image' => [
            'path' => 'files',
            'sizes' => [1920, 1080],
        ],
    ];

    public function nonLocalizedRules(): array
    {
        return [
            'company_id' => [
                'nullable',
                new ExistsWithOldRule(
                    model: $this->model,
                    query: Company::query(),
                ),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                new UniqueRule(
                    model: $this->model,
                    fieldIsLocalized: false,
                    additionalQuery: function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('company_id');

                            if ($this->company_id) {
                                $q->orWhere('company_id', $this->company_id);
                            }
                        });
                    },
                ),
            ],
            ...$this->deletableFileFieldsSingleValidation(
                field: 'image',
                rules: ValidationFileRulesHelper::image(),
            ),
        ];
    }

    protected function additionalValidation(): void
    {
        if ($this->model->exists && $this->model->company_id && $this->company_id) {
            if ((int)$this->company_id !== (int)$this->model->company_id) {
                $this->validator->errors()->add('company_id', __('Изменение поля :field у данной записи запрещено', [
                    'field' => __('fields.company_id'),
                ]));

                return;
            }
        }
    }
}
