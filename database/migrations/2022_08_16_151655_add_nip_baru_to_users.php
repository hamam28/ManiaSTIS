<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNipBaruToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('users', 'nip_baru')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('nip_baru')->nullable()->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->text('nip_baru')->nullable();
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
            $table->dropColumn('nip_baru');
        });
    }
}
