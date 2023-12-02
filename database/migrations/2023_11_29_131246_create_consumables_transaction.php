<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumablesTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumables_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->datetime('purchase_date');
            $table->longText('notes')->nullable();
            $table->boolean('state');
            $table->integer('employee_num');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumables_transaction');
    }
}
