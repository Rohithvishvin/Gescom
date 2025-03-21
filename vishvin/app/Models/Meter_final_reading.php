<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter_final_reading extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'billed_date', 'reading'];

}
