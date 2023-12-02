<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKoderuangToLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('locations', 'koderuang')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->string('koderuang', 5)->change();
            });
        } else {
            Schema::table('locations', function (Blueprint $table) {
                $table->string('koderuang', 5);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('koderuang');
        });
    }
}
