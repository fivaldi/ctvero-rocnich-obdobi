<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->dateTime('contest_start');
            $table->dateTime('contest_end');
            $table->dateTime('submission_start');
            $table->dateTime('submission_end');
            $table->json('options')->nullable();
            $table->timestamps();
        });
        Schema::table('diary', function (Blueprint $table) {
            $table->foreignId('contest_id')->constrained('contest');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->dropForeign('diary_contest_id_foreign');
            $table->dropColumn('contest_id');
        });
        Schema::dropIfExists('contest');
    }
}
