<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $table = "cars";


    public function ports(){
        return $this->hasMany('App\Models\Port'::class);
    }
}
