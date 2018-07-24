<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSalePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_sale_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->dateTime('sale_start');
            $table->dateTime('sale_end');
            $table->string('discount')->nullable();
            $table->string('period_name');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('project_sale_periods');
    }
}
