<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('user_provider', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');
            $table->string('provider_uid');
            $table->string('avatar_url');
            $table->string('provider');
            $table->timestamps();
        });
        Schema::table('diary', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('contest_id')->constrained('user');
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
            $table->dropForeign('diary_user_id_foreign');
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('user_provider');
        Schema::dropIfExists('user');
    }
}
