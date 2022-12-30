<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordChargerDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_charger_detail', function (Blueprint $table) {
            $table->id();
            $table->string('charger_car_id', 10);
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
        Schema::dropIfExists('record_charger_detail');
    }
}
