<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->default(1); // для вкладених категорій
            $table->string('slug')->unique(); // унікальний URL-текст (наприклад: "my-first-category")
            $table->string('title'); // назва
            $table->text('description')->nullable(); // опис, може бути порожнім
            $table->timestamps(); // автоматично створює колонки created_at та updated_at
            $table->softDeletes(); // колонка deleted_at для "м'якого" видалення (без видалення з фізичного диску)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
