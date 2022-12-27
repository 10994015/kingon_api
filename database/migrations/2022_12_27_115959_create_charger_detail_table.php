<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChargerDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charger_detail', function (Blueprint $table) {
            $table->string('school_no', 10)->primary();
            $table->string('charger_car_id', 10);
            $table->char('school_date', 10);
            $table->char('time_seq', 2);
            $table->char('charge_amount', 10);
            $table->char('deposit_device', 2);
            $table->text('statu');
            $table->timestamps();
        });
        DB::unprepared('ALTER TABLE `charger_detail` DROP PRIMARY KEY, ADD PRIMARY KEY (  `school_no`, `charger_car_id`, `school_date`, `time_seq` )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charger_detail');
    }
}
