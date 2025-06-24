<?php

namespace Base\App\Http\Controllers;

use App\Base\Model;
use Base\App\Base\Controller;
use Base\App\Base\Filter;
use Base\App\Base\Search;
use Base\Modules\Seo\Traits\SeoMetaModelTrait;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class ResourceController extends Controller
{
    public function __construct(
        public Model $model,
        protected Search $search = new Search(),
        protected ?Filter $filter = null,
        protected ?string $resourceClass = null,
        protected ?string $formRequestClass = null,
    ) {
        $this->resourceClass ??= $this->model->resourceClass;

        if ($this->filter) {
            Route::bind('model', function ($value) {
                return $this->filter->process($this->model->query())
                    ->where($this->model->getKeyName(), $value)
                    ->firstOrFail();
            });
        } else {
            Route::model('model', $this->model::class);
        }

        $this->middleware(function ($request, $next) {
            if ($this->search !== null) {
                $this->search->setQuery($this->model->query())
                    ->with((array)$request->get('with'))
                    ->filter((array)$request->get('filter'))
                    ->sort((array)$request->get('sort'))
                    ->show((array)$request->get('show'))
                    ->setPageSize((int)$request->get('page-size'))
                    ->additionalQuery();

                if ($this->filter) {
                    $this->filter->process($this->search->query);
                }
            }

            if ($this->formRequestClass !== null) {
                app()->bind(ValidatesWhenResolved::class, $this->formRequestClass);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $paginator = $this->search->query->paginate($this->search->pageSize);
        return $this->resourceClass::collection($paginator)->response();
    }

    public function show(Model $model)
    {
        $model = $this->search->query->findOrFail($model->getKey());

        if (in_array(SeoMetaModelTrait::class, class_uses_recursive($model))) {
            $model->append(['seo_meta', 'seo_meta_as_array']);
        }

        $data = $this->resourceClass::make($model);

        return response()->json($data);
    }

    public function create(ValidatesWhenResolved $request)
    {
        $request->model->safelySave($request->validatedData);
        $request->model->unsetRelations();

        $data = $this->resourceClass::make($request->model);
        return response()->json($data);
    }

    public function update(ValidatesWhenResolved $request)
    {
        $request->model->safelySave($request->validatedData);
        $request->model->unsetRelations();

        $data = $this->resourceClass::make($request->model);
        return response()->json($data);
    }

    public function delete(Model $model)
    {
        $model->safelyDelete();
        return $this->successResponse();
    }

    public function restore(string $value)
    {
        if (!in_array(SoftDeletes::class, class_uses_recursive($this->model))) {
            abort(400, 'Model doesn\'t use soft delete trait');
        }

        $this->search->query->onlyTrashed()->findOrFail($value)->safelyRestore();
        return $this->successResponse();
    }

    public function deleteMultiple()
    {
        $selection = (array)request()->get('selection', []);

        $models = $this->search->query
            ->whereIn($this->model->getRouteKeyName(), $selection)
            ->limit($this->search->pageSize)
            ->get();

        $this->model->safelyDBProcess(function () use ($models) {
            $models->each(function ($model, $key) {
                $model->delete();
            });
        });

        return $this->successResponse();
    }

    public function restoreMultiple()
    {
        if (!in_array(SoftDeletes::class, class_uses_recursive($this->model))) {
            abort(400, 'Model doesn\'t use soft delete trait');
        }

        $selection = (array)request()->get('selection', []);

        $models = $this->search->query
            ->whereIn($this->model->getRouteKeyName(), $selection)
            ->limit($this->search->pageSize)
            ->onlyTrashed()
            ->get();

        $this->model->safelyDBProcess(function () use ($models) {
            $models->each(function ($model, $key) {
                $model->restore();
            });
        });

        return $this->successResponse();
    }
}
