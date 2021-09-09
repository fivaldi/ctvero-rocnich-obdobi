<?php

use Illuminate\Database\Migrations\Migration;

use App\Models\Category;

class MoveImagesToStaticDir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Category::all() as $category) {
            $category->image_src = preg_replace('|^img/|', '/static/img/', $category->image_src);
            $category->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (Category::all() as $category) {
            $category->image_src = preg_replace('|^/static/img/|', 'img/', $category->image_src);
            $category->save();
        }
    }
}
