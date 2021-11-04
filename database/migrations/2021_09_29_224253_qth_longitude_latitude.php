<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Diary;
use App\Http\Utilities;

class QthLongitudeLatitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->float('qth_locator_lon', 9, 6)->after('qth_locator');
            $table->float('qth_locator_lat', 9, 6)->after('qth_locator_lon');
        });

        foreach (Diary::all() as $diary) {
            list($lon, $lat) = Utilities::locatorToGps($diary->qth_locator);
            $diary->qth_locator_lon = $lon;
            $diary->qth_locator_lat = $lat;
            $diary->save();
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
            $table->dropColumn('qth_locator_lon');
            $table->dropColumn('qth_locator_lat');
        });
    }
}
