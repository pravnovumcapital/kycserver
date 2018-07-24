<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstNameLastNameUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->string('first_name');
            $table->string('last_name')->after('name');
            $table->date('date_of_birth')->after('last_name');
            $table->string('phone_number')->after('date_of_birth');
            $table->integer('country_code')->after('phone_number');
            $table->string('device_security_enable')->after('country_code')->nullable();
            $table->string('device_id')->after('device_security_enable')->nullable();
            $table->string('type_of_security')->after('device_id')->nullable();
            $table->string('security_token')->after('type_of_security')->nullable();
            $table->string('status')->after('security_token');
            $table->integer('citizenship_id')->after('status')->nullable();
            $table->string('passport_number')->after('citizenship_id')->nullable();
            $table->string('passport_photo')->after('passport_number')->nullable();
            $table->string('selfie_photo')->after('passport_photo')->nullable();
            $table->string('erc20_address')->after('selfie_photo')->nullable();
        });


                
                
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone_number');
            $table->dropColumn('country_code');
            $table->dropColumn('device_security_enable');
            $table->dropColumn('device_id');
            $table->dropColumn('type_of_security');
            $table->dropColumn('security_token');
            $table->dropColumn('status');
            $table->dropColumn('citizenship_id');
            $table->dropColumn('passport_number');
            $table->dropColumn('passport_photo');
            $table->dropColumn('selfie_photo');
        });
    }
}
