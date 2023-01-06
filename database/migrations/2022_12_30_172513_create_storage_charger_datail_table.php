<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageChargerDatailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_charger_datail', function (Blueprint $table) {
            $table->id();
            $table->string('charger_car_id', 13);
            $table->char('school_date', 10);
            $table->char('time_seq', 2);
            $table->integer('port_no');
            $table->integer('capacity');
            $table->text('statu');
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
        Schema::dropIfExists('storage_charger_datail');
    }
}
