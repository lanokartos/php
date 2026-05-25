<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [];
        $hasSlugColumn = Schema::hasColumn('blog_categories', 'slug');

    $cName = 'Без категорії';
        $categories[] = array_filter([
            'title'     => $cName,
            'slug'      => $hasSlugColumn ? Str::slug($cName) : null, // перетворить "Без категорії" на "bez-katehoriyi"
            'parent_id' => 0,
        ], static fn ($value) => $value !== null);

    for ($i = 1; $i <= 10; $i++) {
        $cName = 'Категорія #'.$i;
        $parentId = ($i > 4) ? rand(1, 4) : 1; // випадкова вкладеність для категорій з ID більше 4

        $categories[] = array_filter([
            'title'     => $cName,
            'slug'      => $hasSlugColumn ? Str::slug($cName) : null,
            'parent_id' => $parentId,
        ], static fn ($value) => $value !== null);
    }

        foreach ($categories as $category) {
            DB::table('blog_categories')->updateOrInsert(
                $hasSlugColumn ? ['slug' => $category['slug']] : ['title' => $category['title']],
                $category,
            );
        }
    }
}
