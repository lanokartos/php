<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Controllers\Api\Blog\BaseController;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }

    public function index()
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return $paginator;
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
}