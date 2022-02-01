<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Add2022Contests extends Migration
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
                [ 'name' => 'Jaro 2022', 'contest_start' => '2022-03-26 00:00:00', 'contest_end' => '2022-03-28 00:00:00', 'submission_start' => '2022-03-26 00:00:00', 'submission_end' => '2022-04-28 00:00:00', 'options' => NULL ],
                [ 'name' => 'Léto 2022', 'contest_start' => '2022-06-25 00:00:00', 'contest_end' => '2022-06-27 00:00:00', 'submission_start' => '2022-06-25 00:00:00', 'submission_end' => '2022-07-27 00:00:00', 'options' => NULL ],
                [ 'name' => 'Podzim 2022', 'contest_start' => '2022-09-24 00:00:00', 'contest_end' => '2022-09-26 00:00:00', 'submission_start' => '2022-09-24 00:00:00', 'submission_end' => '2022-10-26 00:00:00', 'options' => NULL ],
                [ 'name' => 'Zima 2022', 'contest_start' => '2022-12-10 00:00:00', 'contest_end' => '2022-12-12 00:00:00', 'submission_start' => '2022-12-10 00:00:00', 'submission_end' => '2023-01-12 00:00:00', 'options' => NULL ],
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
        DB::table('contest')->where('name', 'Jaro 2022')->delete();
        DB::table('contest')->where('name', 'Léto 2022')->delete();
        DB::table('contest')->where('name', 'Podzim 2022')->delete();
        DB::table('contest')->where('name', 'Zima 2022')->delete();
    }
}
