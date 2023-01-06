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
            $table->string('charger_car_id', 13)->primary(); // 每台車的ID? 每台車的port寫在哪?
            $table->string('school_date', 10); // 什麼的時間?
            $table->string('time_seq', 2);  //什麼的時間?
            $table->string('charge_amount', 10); //每台車的充電量還是每個port的充電量
            $table->string('deposit_device', 2); //port數嗎?
            $table->text('statu'); //每個port的狀態嗎?
            $table->timestamps();
        });
        DB::unprepared('ALTER TABLE `charger_detail` DROP PRIMARY KEY, ADD PRIMARY KEY (  `charger_car_id`, `school_date`, `time_seq` )'); //四個複合式主鍵嗎?
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
