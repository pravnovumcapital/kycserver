<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_bank_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('payment_method_id');
            $table->string('payment_method_name');
            $table->string('account_name');
            $table->text('holder_address');
            $table->string('account_number');
            $table->string('swift_code');
            $table->string('bank_name');
            $table->string('bank_address');
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
        Schema::dropIfExists('project_bank_details');
    }
}
