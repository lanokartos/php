<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Controllers\Api\Blog\BaseController;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    /**
     * Отримання списку категорій з пагінацією.
     */
    public function index()
    {
        // Внутрішній процес: Eloquent виконує SQL-запит з операторами LIMIT та OFFSET.
        // Одночасно запускається другий запит COUNT(*), щоб вирахувати загальну кількість сторінок.
        $paginator = BlogCategory::paginate(5);

        return $paginator; // Автоматично серіалізується в JSON-структуру з метаданими пагінації
    }

    /**
     * Створення нової категорії (Реалізація завдання).
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Якщо користувач не передав кастомний URL-слаг, генеруємо його автоматично з title
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Внутрішній процес: Створення об'єкта, фільтрація через $fillable та виконання SQL INSERT
        $item = BlogCategory::create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно створено',
                'id' => $item->id // Повертаємо ID створеного запису для фронтенду
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Помилка створення'
            ];
        }
    }

    /**
     * Оновлення існуючої категорії.
     */
    public function update(Request $request, string $id)
    {
        $item = BlogCategory::find($id);
        
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

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