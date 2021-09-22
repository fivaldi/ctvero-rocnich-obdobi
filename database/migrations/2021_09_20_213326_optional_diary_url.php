<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Diary;

class OptionalDiaryUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->dropUnique('diary_diary_url_unique');
            $table->string('diary_url')->unique()->nullable()->change();
        });

        foreach (Diary::all() as $diary) {
            if (preg_match(';http://example\.com/(chybny|neexistujici)-odkaz-na-denik-;', $diary->diary_url)) {
                $diary->diary_url = NULL;
                $diary->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->dropUnique('diary_diary_url_unique');
        });

        foreach (Diary::all() as $i => $diary) {
            if (is_null($diary->diary_url)) {
                $diary->diary_url = 'http://example.com/neexistujici-odkaz-na-denik-' . ($i + 1);
                $diary->save();
            }
        }

        Schema::table('diary', function (Blueprint $table) {
            $table->string('diary_url')->unique()->nullable(false)->change();
        });
    }
}
