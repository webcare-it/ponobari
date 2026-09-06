<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourierSettingsToGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Pathao Courier Settings
            $table->string('pathao_client_id')->nullable();
            $table->string('pathao_client_secret')->nullable();
            $table->string('pathao_username')->nullable();
            $table->string('pathao_password')->nullable();
            $table->boolean('pathao_sandbox')->default(false);
            
            // Other courier settings can be added here
            $table->string('steadfast_api_key')->nullable();
            $table->string('steadfast_secret_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'pathao_client_id',
                'pathao_client_secret',
                'pathao_username',
                'pathao_password',
                'pathao_sandbox',
                'steadfast_api_key',
                'steadfast_secret_key'
            ]);
        });
    }
}