<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->integer('type');
            $table->string('name');
            $table->string('mac');
            $table->string('fwver');
            $table->string('trolley_model');
            $table->string('trolley_fwver');
            $table->string('trolley_ts');
            $table->string('trolley_port_number');
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
        Schema::dropIfExists('cars');
    }
}
