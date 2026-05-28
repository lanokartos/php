<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Controllers\Api\Blog\BaseController;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;

class CategoryController extends BaseController
{
    public function index()
    {
        $paginator = BlogCategory::paginate(5);

        return $paginator;
    }

    public function store(Request $request)
    {
        $item = new BlogCategory();
        $item->title = $request->input('title');
        $item->slug = $request->filled('slug')
            ? $request->input('slug')
            : Str::slug($request->input('title'));
        $item->parent_id = $request->input('parent_id');
        $item->description = $request->input('description');
        $item->save();

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно створено',
                'id' => $item->id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Помилка створення'
            ];
        }
    }

    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = BlogCategory::find($id);
        
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $item->title = $request->input('title');
        $item->slug = $request->filled('slug')
            ? $request->input('slug')
            : Str::slug($request->input('title'));
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