<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->string('method_name');
            $table->integer('method_id');
            $table->string('type');
            $table->integer('project_bank_detail_id')->nullable();
            $table->string('price_per_token');
            $table->text('wallet_address')->nullable();
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
        Schema::dropIfExists('project_payment_methods');
    }
}
