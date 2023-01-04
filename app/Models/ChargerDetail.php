<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargerDetail extends Model
{
    use HasFactory;
    protected $table = "charger_detail";
    protected $primaryKey = 'charger_car_id';
}
