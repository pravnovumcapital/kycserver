<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('thumbnail_logo')->nullable();
            $table->string('project_logo')->nullable();
            //$table->dateTime('pre_sale_end');
            $table->text('payment_methods')->nullable();
            $table->text('payment_methods_ids')->nullable();
            $table->string('total_raised')->nullable();
            $table->string('max_raise')->nullable();
            $table->string('website_url')->nullable();
            $table->string('contact_email')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
