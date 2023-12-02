<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoteToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'remote')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('remote')->default(0)->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('remote')->default(0);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('remote');
        });
    }
}
