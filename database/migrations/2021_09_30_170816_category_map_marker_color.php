<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CategoryMapMarkerColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->string('map_marker_color')->after('image_src');
        });

        // Taken from https://www.schemecolor.com/dark-pastels-color-scheme.php
        DB::table('category')->where('id', 1)->update(['map_marker_color' => '#c23b23']);
        DB::table('category')->where('id', 2)->update(['map_marker_color' => '#f39a27']);
        DB::table('category')->where('id', 3)->update(['map_marker_color' => '#579abe']);
        DB::table('category')->where('id', 4)->update(['map_marker_color' => '#eada52']);
        DB::table('category')->where('id', 5)->update(['map_marker_color' => '#03c03c']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('map_marker_color');
        });
    }
}
