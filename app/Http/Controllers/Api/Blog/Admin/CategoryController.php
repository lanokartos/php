<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Controllers\Api\Blog\BaseController;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }

    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->query('all')) {
            return $this->blogCategoryRepository->startConditions()->get();
        }

        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return CategoryResource::collection($paginator);
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми
 
        $item = (new BlogCategory())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $item->title = $request->input('title');
        $item->slug = $request->input('slug');
        $item->parent_id = $request->input('parent_id');
        $item->description = $request->input('description');

        $result = $item->save();

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function show($id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        return $item;
    }

    public function destroy($id)
    {
        $result = BlogCategory::destroy($id);

        if ($result) {
            return ['success' => true];
        } else {
            return response()->json(['message' => 'Помилка видалення'], 500);
        }
    }
}