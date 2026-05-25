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
        Schema::table('blog_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->default(1)->after('id');
            }

            if (! Schema::hasColumn('blog_categories', 'slug')) {
                $table->string('slug')->unique()->after('parent_id');
            }

            if (! Schema::hasColumn('blog_categories', 'title')) {
                $table->string('title')->after('slug');
            }

            if (! Schema::hasColumn('blog_categories', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (! Schema::hasColumn('blog_categories', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_posts', 'category_id')) {
                $table->unsignedBigInteger('category_id')->after('id');
            }

            if (! Schema::hasColumn('blog_posts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('category_id');
            }

            if (! Schema::hasColumn('blog_posts', 'slug')) {
                $table->string('slug')->unique()->after('user_id');
            }

            if (! Schema::hasColumn('blog_posts', 'title')) {
                $table->string('title')->after('slug');
            }

            if (! Schema::hasColumn('blog_posts', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('title');
            }

            if (! Schema::hasColumn('blog_posts', 'content_raw')) {
                $table->text('content_raw')->after('excerpt');
            }

            if (! Schema::hasColumn('blog_posts', 'content_html')) {
                $table->text('content_html')->after('content_raw');
            }

            if (! Schema::hasColumn('blog_posts', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('content_html');
            }

            if (! Schema::hasColumn('blog_posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }

            if (! Schema::hasColumn('blog_posts', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        if (! Schema::hasColumn('blog_posts', 'category_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('blog_categories');
            });
        }

        if (! Schema::hasColumn('blog_posts', 'user_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        if (! Schema::hasColumn('blog_posts', 'is_published')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->index('is_published');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            if (Schema::hasColumn('blog_posts', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }

            if (Schema::hasColumn('blog_posts', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('blog_posts', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }

            foreach (['title', 'excerpt', 'content_raw', 'content_html', 'is_published', 'published_at', 'deleted_at'] as $column) {
                if (Schema::hasColumn('blog_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            if (Schema::hasColumn('blog_categories', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }

            foreach (['parent_id', 'title', 'description', 'deleted_at'] as $column) {
                if (Schema::hasColumn('blog_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};