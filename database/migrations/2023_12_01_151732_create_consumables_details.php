<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumablesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumables_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->integer('category_id')->nullable()->default(null);
            $table->decimal('purchase_cost', 13, 4)->nullable();
            $table->integer('qty')->default(0);
            $table->boolean('requestable')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'innoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumables_details');
    }
}
