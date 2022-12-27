<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;
    protected $table = "ports";

    public function car()
    {
        return $this->belongsTo('App\Models\Car'::class);
    }
}
