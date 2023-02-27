<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Add2023Contests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contest')->insert(
            array(
                [ 'name' => 'Jaro 2023', 'contest_start' => '2023-03-25 00:00:00', 'contest_end' => '2023-03-27 00:00:00', 'submission_start' => '2023-03-25 00:00:00', 'submission_end' => '2023-04-27 00:00:00', 'options' => NULL ],
                [ 'name' => 'Léto 2023', 'contest_start' => '2023-06-24 00:00:00', 'contest_end' => '2023-06-26 00:00:00', 'submission_start' => '2023-06-24 00:00:00', 'submission_end' => '2023-07-26 00:00:00', 'options' => NULL ],
                [ 'name' => 'Podzim 2023', 'contest_start' => '2023-09-23 00:00:00', 'contest_end' => '2023-09-25 00:00:00', 'submission_start' => '2023-09-23 00:00:00', 'submission_end' => '2023-10-25 00:00:00', 'options' => NULL ],
                [ 'name' => 'Zima 2023', 'contest_start' => '2023-12-09 00:00:00', 'contest_end' => '2023-12-11 00:00:00', 'submission_start' => '2023-12-09 00:00:00', 'submission_end' => '2024-01-11 00:00:00', 'options' => NULL ],
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('contest')->where('name', 'Jaro 2023')->delete();
        DB::table('contest')->where('name', 'Léto 2023')->delete();
        DB::table('contest')->where('name', 'Podzim 2023')->delete();
        DB::table('contest')->where('name', 'Zima 2023')->delete();
    }
}
