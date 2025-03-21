<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor_inventory extends Model
{
    public $timestamps = false;

    use HasFactory;
	
	
	 protected $fillable = [
        'box_id',
        'serial_no',
        'unused_meter_serial_no',  // Add this field
        'used_meter_serial_no',
        'division',
        'meter_type',
        'dc_no',
        'contractor_id',
        'created_by',
        // Add any other fields that should be mass assignable
    ];
}
